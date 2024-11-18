<DOCTYPE html>
    <html lang="en">

    <head>
        <title>Test PHP</title>
    </head>

    <body>
        <?php
        class shape
        {
            public $title;
            public $area;
            public function get_area()
            {
                switch ("$this->title") {
                    case "Rectangle":
                        $this->area = ($this->sizeA * $this->sizeB);
                        break;
                    case "Triangle":
                        $p = ($this->sizeA + $this->sizeB + $this->sizeC) / 2;
                        $this->area = round(sqrt($p * ($p - $this->sizeA) * ($p - $this->sizeB) * ($p - $this->sizeC)), 2); //Heron's formula
                        break;
                    case "Circle":
                        $this->area = round((2 * M_PI * $this->radius), 2);
                        break;
                    default:
                        echo "error shape";
                }
                return $this->area;
            }
        }

        class Rectangle extends shape
        {
            public $title = "Rectangle";
            public $sizeA;
            public $sizeB;
            function __construct($sizeA = 0, $sizeB = 0)
            {
                $this->sizeA = $sizeA;
                $this->sizeB = $sizeB;
            }
            function readSizes($line)
            {
                $sizes = explode(" ", $line);
                $this->sizeA = $sizes[0];
                $this->sizeB = $sizes[1];
            }
        }

        class Triangle extends shape
        {
            public $title = "Triangle";
            public $sizeA;
            public $sizeB;
            public $sizeC;
            function __construct($sizeA = 0, $sizeB = 0, $sizeC = 0)
            {
                $this->sizeA = $sizeA;
                $this->sizeB = $sizeB;
                $this->sizeC = $sizeC;
            }
            function readSizes($line)
            {
                $sizes = explode(" ", $line);
                $this->sizeA = $sizes[0];
                $this->sizeB = $sizes[1];
                $this->sizeC = $sizes[2];
            }
        }

        class Circle extends shape
        {
            public $title = "Circle";
            public $radius;
            function __construct($radius = 0)
            {
                $this->radius = $radius;
            }
            function readSizes($line)
            {
                $sizes = explode(" ", $line);
                $this->radius = $sizes[0];
            }
        }

        $db_handle = mysqli_connect("localhost", "phpmyadmin", "phpadmin");
        if ($db_handle === false) {
            die("ПОМИЛКА: Неможливо підключитися." . mysqli_connect_error());
        }
        echo "Підключення до сервера пройшло успішно! <br>";
        mysqli_select_db($db_handle,    "LAB6") or die(mysqli_error());
        echo "База даних була обрана!<br>";
        $result = mysqli_query($db_handle, "SELECT * FROM operation");
        while ($row = mysqli_fetch_assoc($result)) {
            echo "ID: " . $row['ID'] . " operation: " . $row['operation_type'] . "<br>";
        }

        $operation = $sizes = $id = $r_id = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $operation = input($_POST["operation"]);
            $sizes = input($_POST["sizes"]);
            $id = input($_POST["ID"]);
            $r_id = input($_POST["rID"]);
        }

        function input($data)
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        ?>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

            <h2>Add in DB finded area for</h2>
            <input type="radio" name="operation" value="rectangle">rectangle
            <input type="radio" name="operation" value="triangle">triangle
            <input type="radio" name="operation" value="circle">circle<br>
            Sizes: <input type="text" name="sizes">
            <input type="submit" name="submit" value="Add">
            <?php
            if ($operation != "" && $sizes != "") {
                $sql = 'INSERT INTO operation (operation_type) VALUES ("' . $operation . '")';
                $result = mysqli_query($db_handle, $sql);

                if ($operation == "rectangle") {
                    $shape_A = new Rectangle();
                    $shape_A->readSizes($sizes);
                    $sql = 'INSERT INTO value (input, result) VALUES ("' . $sizes . '","' . $shape_A->get_area() . '")';
                    $result = mysqli_query($db_handle, $sql);
                }

                if ($operation == "triangle") {
                    $shape_B = new Triangle();
                    $shape_B->readSizes($sizes);
                    $sql = 'INSERT INTO value (input, result) VALUES ("' . $sizes . '","' . $shape_B->get_area() . '")';
                    $result = mysqli_query($db_handle, $sql);
                }

                if ($operation == "circle") {
                    $shape_C = new Circle();
                    $shape_C->readSizes($sizes);
                    $sql = 'INSERT INTO value (input, result) VALUES ("' . $sizes . '","' . $shape_C->get_area() . '")';
                    $result = mysqli_query($db_handle, $sql);
                }
                echo "Add correctly.";
            } else {
                echo "<br>Please chose/enter all values.";
            }
            ?>

            <h2>Show operation data</h2>
            ID: <input type="text" name="ID">
            <input type="submit" name="submit" value="Show">
            <?php
            if ($id == "?") {
                echo "<br>All enabled ID: ";
                $result = mysqli_query($db_handle, "SELECT * FROM operation");
                while ($row = mysqli_fetch_assoc($result)) {
                    echo $row['ID'] . ", ";
                }
            } elseif ($id != "") {
                $mesg = "";
                $result = mysqli_query($db_handle, "SELECT * FROM operation");
                while ($row = mysqli_fetch_assoc($result)) {
                    if ($row['ID'] == $id) {
                        $mesg = "<br>ID: " . $row['ID'] . " Searche area for " . $row['operation_type'];
                    }
                }
                $result = mysqli_query($db_handle, "SELECT * FROM value");
                while ($row = mysqli_fetch_assoc($result)) {
                    if ($row['ID'] == $id) {
                        echo $mesg . " with sizes: " . $row['input'] . " area =  " . $row['result'];
                    }
                }
            } else {
                echo "<br>Enter ID.";
            }
            ?>
            <h2>Remove data from DB</h2>
            ID for remove: <input type="text" name="rID">
            <input type="submit" name="submit" value="Remove">
            <?php
            if ($r_id != "") {
                echo 'DELETE FROM operation WHERE ID=' . $r_id;
                echo 'DELETE FROM values WHERE ID=' . $r_id;
                $sql = 'DELETE FROM operation WHERE ID=' . $r_id;
                $result = mysqli_query($db_handle, $sql);
                $sql = 'DELETE FROM value WHERE ID=' . $r_id;
                $result = mysqli_query($db_handle, $sql);
            }
            ?>
        </form>
    </body>