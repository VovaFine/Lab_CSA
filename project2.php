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
                echo "Площа фігури $this->title = $this->area см^2<br>";
            }
        }

        class Rectangle extends shape
        {
            public $title = "Rectangle";
            public $sizeA;
            public $sizeB;
            function __construct($sizeA, $sizeB)
            {
                $this->sizeA = $sizeA;
                $this->sizeB = $sizeB;
            }
        }

        class Triangle extends shape
        {
            public $title = "Triangle";
            public $sizeA;
            public $sizeB;
            public $sizeC;
            function __construct($sizeA, $sizeB, $sizeC)
            {
                $this->sizeA = $sizeA;
                $this->sizeB = $sizeB;
                $this->sizeC = $sizeC;
            }
        }

        class Circle extends shape
        {
            public $title = "Circle";
            public $radius;
            function __construct($radius)
            {
                $this->radius = $radius;
            }
        }

        $shape_A = new Rectangle(10, 15);

        $shape_B = new Triangle(10, 15, 11);

        $shape_C = new Circle(5);

        $shape_A->get_area();
        $shape_B->get_area();
        $shape_C->get_area();

        ?>
    </body>


    </html>