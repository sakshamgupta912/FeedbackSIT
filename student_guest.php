<?php
session_start();
if (!isset($_SESSION['student'])) {
    header('Location: index.php');
    exit;
}


require_once "connection.php";
$username = $_SESSION['student']; //gives prn

$branch = $_SESSION['branch_student'];
$branch_check = $branch . '' . "_student";
$branch_teacher = $branch . '' . "_teacher";
$branch_feedback = $branch . '' . "_feedback";

if ($branch_check == "FY") {
    $query = "SELECT * FROM fy_student WHERE prn='$username'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $name = ucwords($row["name"]);
                $student_email = $row["email"];
            }
        } else {
            $name = 'Please Contact Coordinator!';
        }
    } else {
        // Query execution failed
        echo "Error: " . mysqli_error($conn);
    }
} else {
    $query = "SELECT * FROM $branch_check WHERE prn='$username'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $name = ucwords($row["name"]);
                $student_email = $row["email"];
            }
        } else {
            $name = 'Please Contact Coordinator!';
        }
    } else {
        // Query execution failed
        echo "Error: " . mysqli_error($conn);
    }
}





?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="./slick/slick.css">
    <link rel="stylesheet" href="./slick/slick-theme.css" integrity="sha512-6lLUdeQ5uheMFbWm3CP271l14RsX1xtx+J5x2yeIDkkiBpeVTNhTqijME7GgRKKi6hCqovwCoBTlRBEC20M8Mg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

    <link href="./Coordinator Dashboard css and js/assets/libs/chartist/dist/chartist.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="./Coordinator Dashboard css and js/dist/css/style.min.css" rel="stylesheet">

    <!-- Include Footer CSS -->
    <link rel="stylesheet" href="./css/footer_style.css">

    <style>
        body {
            background: #1C2631;

        }

        .container {
            min-height: calc(100vh);

        }

        .wrapper {
            padding: 70px 0;
            overflow-x: hidden;
        }


        .my-slider {
            padding: 0 70px;
        }

        .slick-initialized .slick-slide {
            border-radius: 15px;
            background: #DFDFDF;

            color: black;
            min-height: 50vh;
            margin: 0 15px 0 0;


        }



        .slick-next,
        .slick-prev {
            z-index: 5;
        }

        .slick-next {
            right: 15px;
        }

        .slick-prev {
            left: 15px;
        }

        .slick-next:before,
        .slick-prev:before {
            color: #000;
            font-size: 26px;
        }



        /* Add the following styles to hide the arrows */
        .slick-arrow {}
    </style>

    <style>
   

        input[type="radio"] {
            appearance: none;
            -webkit-appearance: none;
            width: 20px;
            height: 20px;
            border: 1px solid #fff;
            border-radius: 50%;
            margin-right: 10px;
            background-color: transparent;
            position: relative;
            top: 6px;
        }

        input[type="radio"]:checked::before {
            content: "";
            display: block;
            width: 12px;
            height: 12px;
            background-color: #273444;
            border-radius: 50%;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation: appear 0.4s;
        }

 

        @keyframes appear {
            0% {
                transform: translate(-50%, -50%) scale(0);
                background-color: #fff;
            }

            45% {
                transform: translate(-50%, -50%) scale(1.6);
                background-color: #273444;
            }

            50% {
                transform: translate(-50%, -50%) scale(1.7);
                background-color: #273444;
            }

            55% {
                transform: translate(-50%, -50%) scale(1.6);
            }

            100% {
                transform: translate(-50%, -50%) scale(1);
                background-color: #273444;
            }
        }
    </style>



</head>

<body>


    <div class="container d-flex align-items-center justify-content-center px-0 mb-3">



        <div class="row py-0 d-flex justify-content-center wrapper " style="width: 100%">
            <div style="text-align: center;">
                <p style="color:white;font-size:30px;margin:0 ">Welcome <?php echo $username; ?> !</h>



            </div>
            <form method="post" action="submit_feedback.php" style="display:inline-block;background:none">

                <div class="my-slider px-0 py-3">

                    <?php


$query = "SELECT * FROM guest_student WHERE student_email='$student_email'";
$result = mysqli_query($conn, $query);
$slideNumber=0;

if (mysqli_num_rows($result) == 0) {
    echo "Please attend a guest lecture to give feedback.";
} else {
    $row = mysqli_fetch_assoc($result);
    if ($row['is_valid'] == 0) {
        echo "Feedback Already Filled.";
    } else {
        $guest_id = $row['guest_id'];
        $query = "SELECT * FROM guest WHERE id='$guest_id' AND is_valid='1'";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $guest_name = $row['guest_name'];
            $guest_date = $row['guest_date'];
            $guest_topic = $row['guest_topic'];

            echo "Guest Name: $guest_name<br>";
            echo "Guest Date: $guest_date<br>";
            echo "Guest Topic: $guest_topic<br>";

            // Retrieve questions from question table
            $query = "SELECT questions FROM question WHERE type='guest'";
            $result = mysqli_query($conn, $query);
            $question_number = 1;

            while ($row = mysqli_fetch_assoc($result)) {
                $question = $row['questions'];
                echo "$question_number. $question<br>";
                echo "<input type='radio' name='feedback[$guest_id][$question_number]' value='1' required> 1  ";
                echo "<input type='radio' name='feedback[$guest_id][$question_number]' value='2' required> 2  ";
                echo "<input type='radio' name='feedback[$guest_id][$question_number]' value='3' required> 3  ";
                echo "<input type='radio' name='feedback[$guest_id][$question_number]' value='4' required> 4  ";
                echo "<input type='radio' name='feedback[$guest_id][$question_number]' value='5' required> 5  <br>";
                $question_number++;
            }}


                            echo "</div>";
                            echo "</div>";

                            $slideNumber++;
                        }
                        echo "<div class='anything_else'> <label style='margin:10px'>Feedback:</label><input style='margin:10px' type='text' name='remark[$username]' size='35'></div>";


                        // $_SESSION['prn'] = $username;
                        // $_SESSION['student_name'] = $student_name;
                        // $_SESSION['acad_year'] = $acad_year;
                        // $_SESSION['branch'] = $branch;
                        // $_SESSION['class'] = $class;
                        // $_SESSION['branch_feedback'] = $branch_feedback;
                        // $_SESSION['branch_check'] = $branch_check;
                        // $_SESSION['branch_teacher'] = $branch_teacher;
                        $try = 0;

                        $slideNumber++;
}
                    ?>

                </div>




                <input class="form-submit" type='submit' name='submit' value='Submit Feedback' style="display:none">
                <button class='slide-prev btn btn-primary me-1' style="border-radius: 22px;margin:10px;margin-left:0px">Previous Slide</button>
                <button class='slide-next btn btn-primary' style="border-radius: 22px;margin:15px;margin-left:0px">Next Slide</button>
            </form>
            <button class='btn btn-danger' style="max-width:150px;border-radius: 22px" onclick="checkAllRadio()">Submit Feedback</button>


        </div>



    </div>





    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script type="text/javascript" src="./slick/slick.min.js"></script>
    <script type="text/javascript">
        var total_questions = 1;





        $(document).ready(function() {



            $('.my-slider').slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                arrows: false,
                dots: false,
                speed: 300,
                infinite: false,
                autoplaySpeed: 5000,
                autoplay: false,
                swipe: false,

            });


            $('.slide-prev').click(function(e) {
                e.preventDefault();
                $('.my-slider').slick('slickPrev');
            });

            $('.slide-next').click(function(e) {
                e.preventDefault();

                console.log("");
                

                var activeSlide = document.querySelector('.slick-active');
                if (activeSlide.classList.contains("anything_else"))
                {
                        alert('Click Submit Button')
                }
                else
                {
                    var radioButtons = activeSlide.querySelectorAll('input[type="radio"]');
                var isFilled = true;
                var count = 0;

                radioButtons.forEach(function(radioButton) {
                    if (radioButton.checked === true) {
                        count++;
                    }
                });


                if (count < total_questions) { // 9 questions 


                    alert('Please fill in all the radio buttons on the active slide.');
                    return false; // Prevent carousel slide if any radio button is not filled
                } else {

                    $('.my-slider').slick('slickNext');


                }
                }
               
            });

        });






        function checkAllRadio() {

            var radioButtons = document.querySelectorAll('input[type="radio"]');
            var isFilled = true;
            var count = 0;


            radioButtons.forEach(function(radioButton) {
                if (radioButton.checked === true) {
                    count++;
                }
            });
            var totalSlides = $('.carousel-item').length;
            var total_buttons = totalSlides * (total_questions);
            console.log(count);
            if (count < total_buttons) {
                alert('Please fill in all the options in every slide');
            } else {
                $('.form-submit').click();
            }

        }
    </script>

    <!-- Add the following JavaScript code after the existing code -->




</body>

<footer class="site-footer footer-bottom d-flex py-1">

    <div class="container" style="min-width:100%;min-height:auto">
        <hr class="mt-3 mb-2" style="color: #CDCDCD;">
        <div class="row">
            <div class="col-xs-12 col-md-4 copyright-text">


                <p style="font-size: 14px;font-weight: 700;color: #CDCDCD;;">Feedback |
                    <a>Copyright &copy; 2023 </a>
                </p>
            </div>

            <div class="col-xs-12 col-md-4 ">


                <ul class="footer-links text-center">

                    <li><a style="font-size: 14px;font-weight: 700;color: #CDCDCD;;">Ideation By Dr. Deepali Vora, Head CS IT</a></li>


                </ul>
            </div>

            <div class="col-xs-12 col-md-4 ">

                <ul class="footer-links text-right custom-developed-by">
                    <li><a style="font-size: 14px;font-weight: 700;color: #CDCDCD;;">Developed By: </a>
                        <a href="https://www.linkedin.com/in/skp2208/" style="font-size: 14px;font-weight: 700;color: #CDCDCD;;">Swayam Pendgaonkar</a>
                    </li>
                    <li><a href="https://www.linkedin.com/in/sakshamgupta912/" style="font-size: 14px;font-weight: 700;color: #CDCDCD;;">Saksham Gupta </a>
                        <a href="https://www.linkedin.com/in/yajushreshtha-shukla/" style="font-size: 14px;font-weight: 700;color: #CDCDCD;;">Yajushreshtha Shukla</a>
                    </li>

                </ul>
            </div>

        </div>

    </div>

    </div>
</footer>

</html>