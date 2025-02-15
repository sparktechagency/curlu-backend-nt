<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Payment Success</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f7fc;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }

            .container {
                text-align: center;
                background-color: #ffffff;
                padding: 50px;
                border-radius: 10px;
                box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
                max-width: 650px;
                width: 100%;
                margin: 20px;
            }

            .checkmark-container {
                width: 120px;
                height: 120px;
                display: inline-block;
                margin-bottom: 20px;
                border-radius: 50%;
                background-color: #e8f5e9;
                padding: 15px;
                box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
                animation: scaleCheckmark 1s ease-out forwards;
            }

            svg {
                width: 100%;
                height: 100%;
                fill: #4caf50;
                display: block;
                margin: 0 auto;
            }

            h1 {
                color: #4caf50;
                font-size: 36px;
                margin: 20px 0;
            }

            p {
                color: #777;
                font-size: 18px;
                margin-bottom: 30px;
            }

            @keyframes scaleCheckmark {
                0% {
                    transform: scale(0);
                }
                50% {
                    transform: scale(1.2);
                }
                100% {
                    transform: scale(1);
                }
            }

            .btn {
                padding: 12px 30px;
                background-color: #4caf50;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-size: 18px;
                text-decoration: none;
            }

            .btn:hover {
                background-color: #45a049;
            }

            /* Responsive Design */
            @media (max-width: 768px) {
                .container {
                    padding: 30px;
                }

                h1 {
                    font-size: 28px;
                }

                p {
                    font-size: 16px;
                }

                .checkmark-container {
                    width: 100px;
                    height: 100px;
                }

                .btn {
                    padding: 10px 25px;
                    font-size: 16px;
                }
            }

            @media (max-width: 480px) {
                .container {
                    padding: 20px;
                }

                h1 {
                    font-size: 24px;
                }

                p {
                    font-size: 14px;
                }

                .checkmark-container {
                    width: 80px;
                    height: 80px;
                }

                .btn {
                    padding: 8px 20px;
                    font-size: 14px;
                }
            }
        </style>
    </head>
    <body>
        <div class="container">
            <!-- Checkmark SVG -->
            <div class="checkmark-container">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path
                        d="M9 19.5L4.5 15L6 13.5L9 17L18 8L19.5 9.5L9 19.5Z"
                    />
                </svg>
            </div>
            <h1>Payment Successful</h1>
            <p>
                Your payment has been processed successfully. Thank you for your
                purchase!
            </p>
        </div>

        <script>
            // Trigger the animation for the checkmark after the page is loaded
            window.onload = function () {
                setTimeout(() => {
                    document.querySelector(
                        ".checkmark-container"
                    ).style.animation = "scaleCheckmark 1s ease-out forwards";
                }, 500);
            };
        </script>
    </body>
</html>
