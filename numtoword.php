<?php
/*
*       Script converts numbers (digits) into word representation e.g.:
*	123 -> "one hundred twenty-three"
*	Script uses long-scale naming system
*	Max value to convert is 999 999 999 999 999 999
*/


class Num2Word
{

     /**
      * @var int $number stores number as integer
      */
     private $number;
     /**
      * @var int $length length of the number
      */
     private $length;

     private $digits = array("0" => "zero", "1" => "one", "2" => "two", "3" => "three", "4" => "four", "5" => "five", "6" => "six", "7" => "seven", "8" => "eight", "9" => "nine");
     private $digits1 = array("0" => "ten", "1" => "eleven", "2" => "twelve", "3" => "thirteen", "4" => "fourteen", "5" => "fifteen", "6" => "sixteen", "7" => "seventeen", "8" => "eighteen", "9" => "nineteen");
     private $digits2 = array("1" => "ten", "2" => "twenty", "3" => "thirty", "4" => "forty", "5" => "fifty", "6" => "sixty", "7" => "seventy", "8" => "eighty", "9" => "ninety");
     private $digits3 = array(1 => "hundred", 2 => "thousand", 3 => "million", 4 => "milliard", 5 => "billion", 6 => "billiard");

     /**
      * @param string $num reference to string representing number
      * @return boolean
      */
     private function checkNum(&$num)
     {
          $search = array(" ", "\t", "\n", "\r", "\0", "\x0B");
          $num = str_replace($search, "", $num); //search for white characters and remove them
          if (strlen($num) > 1 && $num[0] == "0") return false; //if number start from '0' return false
          return ctype_digit($num);
     }

     /**
      * Converts 2-digit numbers into word format
      * @param string $dblDig reference to string representing number
      * @return string
      */
     private function readDouble($dblDig)
     {
          if ($dblDig[0] == 1) // check if it's value bewteen 10 and 19
               return (string)$this->digits1[(string)$dblDig[1]];
          elseif ($dblDig[1] == 0) // check if it's round double digit number (10,20,30...)
               return (string)$this->digits2[(string)$dblDig[0]];
          else return (string)$this->digits2[(string)$dblDig[0]] . "-" . $this->digits[(string)$dblDig[1]]; //everything else about two digit numbers
     }

     /**
      * Converts 3-digit numbers into word format
      * @param string $triple reference to string representing number
      * @return string
      */
     private function readTriple($triple)
     {
          $word = "";
          $subhpieces = strlen($triple);
          if (3 == $subhpieces) {
               $word = $this->digits[(string)$triple[0]] . " hundred " . $this->readDouble(substr($triple, 1, 2));
          } elseif (2 == $subhpieces) $word = $this->readDouble($triple);
          else {
               $word = $this->digits[(string)5];
               // $triple = "1234"
               // $word = $this->digits[(string)$triple[0]]
               //  . " hundred " . $this->readDouble(substr($triple, 1, 2));
               echo "\n\n\n";
               var_dump($word);
          }

          return $word;
     }

     /**
      * Divides whole number into up to 3-digits parts,
      * first part consists of 1-3 digits
      * @param string $num reference to string representing number
      * @return string
      */
     public function readNum($num)
     {
          $word = "";
          if ($this->length > 3) {
               $extraDigits = $this->length % 3;
               if (!$extraDigits)
                    $pieces = str_split($num, 3);
               else
                    $pieces = array_merge(array(substr($num, 0, $extraDigits)), str_split(substr($num, $extraDigits), 3));
               $hpieces = count($pieces);
               if ($hpieces > 1)
                    for ($i = $hpieces; $i > 0; $i--)  //variable $i lets choose name of number from digits3 array
                    {
                         if (1 == $i) $word .= $this->readTriple($pieces[$hpieces - $i]);
                         else
                              $word .= $this->readTriple($pieces[$hpieces - $i]) . " " . $this->digits3[$i] . " ";
                    }
               else $word = $this->readTriple($pieces[0]); //it's just one part to work on it - 3-digit number less or equal 999
          } else $word = $this->readTriple($num);
          return $word;
     }

     function __construct($num)
     {
          if ($this->checkNum($num)) {
               $this->number = $num;
               $this->length = strlen($num);
          } else 
          die("Error: It isn't a number");
     }
}


// if (2 == $argc) {
//      $s = Num2Word($argv[1]);
//      return $s->readNum($s->number);
// } else {
//      echo "wrong number of arguments";
//      return 0;
// }
$num_to_word = new Num2Word(123);
// $a = Num2Word(123);
echo "\n" .$num_to_word->readNum('4539'); 
