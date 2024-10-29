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
                $message = "Площа фігури $this->title = $this->area см^2";
                $file = fopen('output.txt', 'a');
                fwrite($file, $message . "\n");
                fclose($file);
                echo $message . "<br>";
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
            function readFile($line)
            {
                $fileContent = file("input.txt");
                $sizes = explode(" ", $fileContent[$line]);
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
            function readFile($line)
            {
                $fileContent = file("input.txt");
                $sizes = explode(" ", $fileContent[$line]);
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
            function readFile($line)
            {
                $fileContent = file("input.txt");
                $sizes = explode(" ", $fileContent[$line]);
                $this->radius = $sizes[0];
            }
        }

        $shape_A = new Rectangle();

        $shape_B = new Triangle();

        $shape_C = new Circle();

        $shape_A->readFile(0);
        $shape_B->readFile(1);
        $shape_C->readFile(2);

        $shape_A->get_area();
        $shape_B->get_area();
        $shape_C->get_area();
        ?>
    </body>


    </html>