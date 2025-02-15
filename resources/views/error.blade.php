<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Payment Error</title>
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

            .error-container {
                width: 120px;
                height: 120px;
                display: inline-block;
                margin-bottom: 20px;
                border-radius: 50%;
                background-color: #f8d7da;
                padding: 15px;
                box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
                animation: scaleError 1s ease-out forwards;
            }

            svg {
                width: 100%;
                height: 100%;
                fill: #f44336;
                display: block;
                margin: 0 auto;
            }

            h1 {
                color: #f44336;
                font-size: 36px;
                margin: 20px 0;
            }

            p {
                color: #777;
                font-size: 18px;
                margin-bottom: 30px;
            }

            @keyframes scaleError {
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
                background-color: #f44336;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-size: 18px;
                text-decoration: none;
            }

            .btn:hover {
                background-color: #d32f2f;
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

                .error-container {
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

                .error-container {
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
            <!-- Error SVG -->
            <div class="error-container">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path
                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14h2v2h-2zm0-10h2v8h-2z"
                    />
                </svg>
            </div>
            <h1>Payment Failed</h1>
            <p>Your payment has already been processed.</p>
        </div>

        <script>
            // Trigger the animation for the error icon after the page is loaded
            window.onload = function () {
                setTimeout(() => {
                    document.querySelector(".error-container").style.animation =
                        "scaleError 1s ease-out forwards";
                }, 500);
            };
        </script>
    </body>
</html>
