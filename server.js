const express = require("express");
const { createServer } = require("http");
const { Server } = require("socket.io");

const app = express();
const server = createServer(app);
const io = new Server(server, {
    cors: {
        origin: "*",
    },
});

const users = {};
const groups = {};

io.on("connection", (socket) => {
    console.log(`User connected: ${socket.id}`);

    // 'login' event with socket ID
    socket.on("login", ({ userId }) => {
        users[userId] = socket.id;
        console.log(`User logged in: ${userId}`);
        io.emit("login", userId);
    });

    // private messages
    socket.on("private-message", ({ receiverId, message }) => {
        const senderId = socket.id;
        console.log(
            `Sending private message from ${senderId} to ${receiverId}: ${message}`
        );

        // Check if the receiver is online
        if (users[receiverId]) {
            io.to(users[receiverId]).emit("private-message", {
                senderId,
                message,
            });
            io.to(senderId).emit("private-message", {
                senderId,
                message,
            });
        } else {
            console.log(`User ${receiverId} not found or offline.`);
        }
    });

    // Join a group
    socket.on("join_group", ({ userId, groupId }) => {
        socket.join(groupId);
        if (!groups[groupId]) {
            groups[groupId] = [];
        }
        groups[groupId].push(userId);
        console.log(`User ${userId} joined group ${groupId}`);

        io.to(groupId).emit("join_group", `${userId} has joined the group.`);
    });

    // Leave a group
    socket.on("leave_group", ({ userId, groupId }) => {
        socket.leave(groupId); // Remove user from the group room
        if (groups[groupId]) {
            groups[groupId] = groups[groupId].filter((id) => id !== userId); // Remove user from group list
        }
        console.log(`User ${userId} left group ${groupId}`);

        io.to(groupId).emit("leave_group", `${userId} has left the group.`);
    });

    // group messages
    socket.on("group_message", ({ groupId, senderId, message }) => {
        console.log(`Group message in ${groupId} from ${senderId}: ${message}`);

        io.to(groupId).emit("group_message", { senderId, message });
    });

    // Handle user disconnection and clean up
    socket.on("disconnect", () => {
        console.log(`User disconnected: ${socket.id}`);

        // Clean up user from the users object
        for (const [userId, socketId] of Object.entries(users)) {
            if (socketId === socket.id) {
                delete users[userId];
                break;
            }
        }
        console.log("Current users:", users);
    });
});

server.listen(3000, "127.0.0.1", () => {
    console.log("Server running at http://127.0.0.1:3000");
});
