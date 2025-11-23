<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | The Amonds</title>
    <link rel="stylesheet" href="user_css/index.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7efe5; /* Creamy beige background */
            color: #4b2e05;
        }

        header {
            background-color: #d2b48c;
            color: #fff;
            padding: 15px 0;
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            letter-spacing: 1px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
        }

        .container {
            width: 85%;
            margin: 40px auto;
            padding: 30px;
            background: #fffaf5;
            border-radius: 12px;
            box-shadow: 0 0 25px rgba(180, 120, 60, 0.15);
        }

        h1, h2 {
            color: #5c4033;
            text-align: center;
            font-weight: 600;
            text-shadow: 0 0 5px rgba(240, 200, 160, 0.3);
        }

        .intro {
            text-align: center;
            margin-bottom: 25px;
            font-size: 16px;
            line-height: 1.8;
            color: #4b2e05;
        }

        .story, .mission, .team {
            background: #fffaf3;
            padding: 25px;
            border-radius: 10px;
            margin-top: 20px;
            box-shadow: 0 3px 15px rgba(150, 100, 50, 0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .story:hover, .mission:hover, .team:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 20px rgba(120, 70, 30, 0.2);
        }

        p {
            color: #4b2e05;
            line-height: 1.8;
            font-size: 15px;
        }

        .team-members {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-top: 15px;
        }

        .member {
            background: #fdf6ef;
            padding: 15px;
            border-radius: 10px;
            width: 250px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(100, 60, 30, 0.1);
            transition: 0.3s;
        }

        .member:hover {
            transform: scale(1.03);
            box-shadow: 0 4px 15px rgba(100, 60, 30, 0.2);
        }

        .member img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 10px;
            border: 3px solid #d2b48c;
        }

        .member h3 {
            color: #b6854d;
            margin: 5px 0;
            font-size: 17px;
        }

        .member p {
            font-size: 14px;
            color: #4b2e05;
        }

        @media (max-width: 768px) {
            .container {
                width: 90%;
                padding: 20px;
            }
            .member {
                width: 80%;
            }
        }
    </style>
</head>
<body>

<?php include 'components/header.php'; ?>

<header>
    <h1>ABOUT THE AMONDS</h1>
</header>

<div class="container">
    <div class="intro">
        <p>Welcome to <strong>The Amonds</strong> — your cozy corner for handcrafted cakes, pastries, and delightful brews. We’re passionate about serving warmth, comfort, and a taste of home in every bite.</p>
    </div>

    <div class="story">
        <h2>Our Story</h2>
        <p>The Amonds began as a small family dream — to create a café where people could slow down and enjoy life's sweet moments. From humble beginnings near San Pedro, we’ve grown into a local favorite, known for our freshly baked cakes, signature drinks, and heartwarming atmosphere.</p>
    </div>

    <div class="mission">
        <h2>Our Mission</h2>
        <p>Our mission is simple: to bring joy through quality ingredients, creativity, and genuine hospitality. We aim to make every visit memorable, whether you’re celebrating, studying, or simply relaxing with friends.</p>
    </div>

    <div class="team">
        <h2>Meet Our Team</h2>
        <div class="team-members">
            <div class="member">
                <img src="images/idpic.jpg" alt="Owner">
                <h3>Bryan Russel Belen</h3>
                <p>Owner</p>
            </div>
       
            <div class="member">
                <img src="images/dpnisantos.jpg" alt="Chef">
                <h3>Allie Santos</h3>
                <p>Manager</p>
            </div>

                        <div class="member">
                <img src="images/Donnabelle.jpg" alt="Chef">
                <h3>Donnabelle Modes</h3>
                <p>Head Chef</p>
            </div>

            
        </div>
    </div>
</div>

</body>
</html>
