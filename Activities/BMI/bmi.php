<?php

class BMICalculator {
    private $height;
    private $weight;
    
    public function __construct($height, $weight) {
        $this->height = $height;
        $this->weight = $weight;
    }
    
    public function calculateBMI() {
        $heightInMeters = $this->height / 100; 
        $bmi = round(($this->weight / ($heightInMeters * $heightInMeters)), 2);
        return $bmi;
    }

    public function getBMICategory($bmi) {
        if ($bmi < 18.5) {
            return "Underweight - Kumaen Ka KASEEEEE!";
        } elseif ($bmi >= 18.5 && $bmi <= 24.9) {
            return "Normal - Nice CONGRATTTSSSS! ";
        } elseif ($bmi >= 25 && $bmi <= 29.9) {
            return "Overweight - Okay lang yan BOSSSS!";
        } elseif ($bmi >= 30 && $bmi <= 34.9) {
            return "Obese - Tara Mag-DIETTTT!";
        } else {
            return "Extreme Obesity - Watch 'The Sugar Film' ";
        }
    }
    
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $height = $_POST['height'];
    $weight = $_POST['weight'];
    
    if ($height == '' || $weight == '') {
        $error = "The input values are required.";
    } elseif (!is_numeric($height) || !is_numeric($weight)) {
        $error = "The input value must be a number only.";
    } else {
        $bmiCalculator = new BMICalculator($height, $weight);
        $bmi = $bmiCalculator->calculateBMI();
        $message = $bmiCalculator->getBMICategory($bmi);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BMI Calculator</title>
    <style>
        
        body {
        background: linear-gradient(90deg, #2cd9ff, #7effb2);
        background-size: 400% 400%;
        animation: moving 2.5s infinite ease-in-out;
        margin: 0;
        padding: 0;
    }

    @keyframes moving {
        0% {
            background-position: 0 50%; 
        } 
        50% {
            background-position: 100% 50%;
        }
        100% {
            background-position: 0 50%;
        }
    }

        .container{
            display: flex;
            justify-content: center;
            align-items: center;
            border: 2px solid black;
            width: 38%;
            margin: auto;
            padding: 10px;
            margin-top: 10%;
            border-radius: 8px;
        }

        .container-1{
            margin: 0;
        } 

        .container h2{
            border-bottom: 1px solid;
        }

        button{
            background-color: blue;
            padding: 8px;
            border-radius: 10px;
            color: white;
        }

        button:hover{
            background-color: black;
            padding: 8px;
            color: white;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="container-1">
    <h2>BMI Calculator</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="height"><b>Height (in cm):</b></label>
        <input type="number" id="height" name="height" min="0" required><br><br>
        <label for="weight"><b>Weight (in kg):</b></label>
        <input type="number" id="weight" name="weight" min="0" required><br><br>
        <button type="submit">Calculate BMI</button>
    </form>
    <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php else: ?>
            <p><b>Your BMI is: </b><u><?php echo $bmi; ?></u></p>
            <p><b>Your BMI Category is: </b><u><?php echo $message; ?></u></p>
        <?php endif; ?>
    <?php endif; ?>
    </div>
    </div>

</body>
</html>