<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Verification | Magic HR</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
            color: #333;
        }

        h1 {
            color: #007bff;
        }

        p {
            margin-bottom: 15px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
        }

        .cta-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }

        /* Add your own styles as needed */

        @media only screen and (max-width: 600px) {
            body {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <p>Dear {{ $data['name'] }},</p>
        <p>We're thrilled to welcome you to the Magic HR! Get ready to embark on an exciting journey with us.</p>
        <br />


        <p>Please verify the following details.</p>
        
        <br />
        <br />
        <!-- Additional content as needed -->

        <p>If you have any questions or need assistance, feel free to reply to this email. We're here to help!</p>

        <p>Best regards,<br>Magic HR</p>
    </div>
</body>
</html>
