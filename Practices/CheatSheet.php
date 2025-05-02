<!DOCTYPE html>
<html>
<head>
    <title>Date and Time Formats</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Date and Time Formats</h2>
    <table>
        <tr>
            <th>Format</th>
            <th>Syntax</th>
            <th>Output</th>
        </tr>
        <tr>
            <td>Full Year</td>
            <td>Y</td>
            <td><?php echo date("Y"); ?></td>
        </tr>
        <tr>
            <td>Two Digit Year</td>
            <td>y</td>
            <td><?php echo date("y"); ?></td>
        </tr>
        <tr>
            <td>Short Month</td>
            <td>M</td>
            <td><?php echo date("M"); ?></td>
        </tr>
        <tr>
            <td>Numeric Month with Leading Zeros</td>
            <td>m</td>
            <td><?php echo date("m"); ?></td>
        </tr>
        <tr>
            <td>Full Month</td>
            <td>F</td>
            <td><?php echo date("F"); ?></td>
        </tr>
        <tr>
            <td>Day with Leading Zeros</td>
            <td>d</td>
            <td><?php echo date("d"); ?></td>
        </tr>
        <tr>
            <td>Short Day</td>
            <td>D</td>
            <td><?php echo date("D"); ?></td>
        </tr>
        <tr>
            <td>Full Day</td>
            <td>l</td>
            <td><?php echo date("l"); ?></td>
        </tr>
        <tr>
            <td>ISO-8601 Day of the Week</td>
            <td>N</td>
            <td><?php echo date("N"); ?></td>
        </tr>
        <tr>
            <td>Numeric Day of the Week</td>
            <td>w</td>
            <td><?php echo date("w"); ?></td>
        </tr>
        <tr>
            <td>24-hour Format</td>
            <td>H</td>
            <td><?php echo date("H"); ?></td>
        </tr>
        <tr>
            <td>12-hour Format</td>
            <td>h</td>
            <td><?php echo date("h"); ?></td>
        </tr>
        <tr>
            <td>Minutes</td>
            <td>i</td>
            <td><?php echo date("i"); ?></td>
        </tr>
        <tr>
            <td>Seconds</td>
            <td>s</td>
            <td><?php echo date("s"); ?></td>
        </tr>
        <tr>
            <td>AM/PM</td>
            <td>A</td>
            <td><?php echo date("A"); ?></td>
        </tr>
        <tr>
            <td>am/pm</td>
            <td>a</td>
            <td><?php echo date("a"); ?></td>
        </tr>
        <tr>
            <td>ISO-8601 Week Number</td>
            <td>W</td>
            <td><?php echo date("W"); ?></td>
        </tr>
        <tr>
            <td>Milliseconds</td>
            <td>v</td>
            <td><?php echo date("v"); ?></td>
        </tr>
        <tr>
            <td>Timezone</td>
            <td>e</td>
            <td><?php echo date("e"); ?></td>
        </tr>
        <tr>
            <td>Timezone Abbreviation</td>
            <td>T</td>
            <td><?php echo date("T"); ?></td>
        </tr>
        <tr>
            <td>Day of the Year</td>
            <td>z</td>
            <td><?php echo date("z"); ?></td>
        </tr>
        <tr>
            <td>Week Number of the Year (ISO-8601)</td>
            <td>W</td>
            <td><?php echo date("W"); ?></td>
        </tr>
        <tr>
            <td>Microseconds</td>
            <td>u</td>
            <td><?php echo date("u"); ?></td>
        </tr>
        <tr>
            <td>Day of the Month without Leading Zeros</td>
            <td>j</td>
            <td><?php echo date("j"); ?></td>
        </tr>
        <tr>
            <td>Day of the Week (Numeric Representation, 0 for Sunday)</td>
            <td>w</td>
            <td><?php echo date("w"); ?></td>
        </tr>
        <tr>
            <td>Day of the Week (Textual Representation)</td>
            <td>l</td>
            <td><?php echo date("l"); ?></td>
        </tr>
        <tr>
            <td>Timezone Offset (Difference to Greenwich Mean Time)</td>
            <td>O</td>
            <td><?php echo date("O"); ?></td>
        </tr>
        <tr>
            <td>Timezone Offset (Difference to Greenwich Mean Time, including colon)</td>
            <td>P</td>
            <td><?php echo date("P"); ?></td>
        </tr>
    </table>

    <!-- ---------------------------------------------- -->

    <h2>String Functions</h2>
    <?php $sampleString = "   Hello, world!   "; ?>
    <table>
        <tr>
            <th>Operation</th>
            <th>Syntax</th>
            <th>Output</th>
        </tr>
        <tr>
            <td>Original String</td>
            <td>N/A</td>
            <td><?php echo "Hello, world!"; ?></td>
        </tr>
        <tr>
            <td>Trimmed String</td>
            <td>trim()</td>
            <td><?php $trimmedString = trim($sampleString); echo $trimmedString; ?></td>
        </tr>
        <tr>
            <td>Uppercase String</td>
            <td>strtoupper()</td>
            <td><?php $upperCaseString = strtoupper($sampleString); echo $upperCaseString; ?></td>
        </tr>
        <tr>
            <td>Lowercase String</td>
            <td>strtolower()</td>
            <td><?php $lowerCaseString = strtolower($sampleString); echo $lowerCaseString; ?></td>
        </tr>
        <tr>
            <td>Length of String</td>
            <td>strlen()</td>
            <td><?php $stringLength = strlen($sampleString); echo $stringLength; ?></td>
        </tr>
        <tr>
            <td>Replaced String</td>
            <td>str_replace()</td>
            <td><?php $replacedString = str_replace("world", "Universe", $sampleString); echo $replacedString; ?></td>
        </tr>
        <tr>
            <td>Reversed String</td>
            <td>strrev()</td>
            <td><?php $reversedString = strrev($sampleString); echo $reversedString; ?></td>
        </tr>
        <tr>
            <td>Position of 'World'</td>
            <td>strpos()</td>
            <td><?php $position = strpos($sampleString, "world"); echo $position; ?></td>
        </tr>
        <tr>
            <td>Convert First Character to Uppercase </td>
            <td>ucfirst()</td>
            <td><?php $ucFirstString = ucfirst($sampleString); echo $ucFirstString; ?></td>
        </tr>
        <tr>
            <td>Uppercase First Letter of Each Word</td>
            <td>ucwords()</td>
            <td><?php $ucWordsString = ucwords($sampleString); echo $ucWordsString; ?></td>
        </tr>
        <tr>
            <td>Convert First Character to Lowercase</td>
            <td>lcfirst()</td>
            <td><?php $lowerFirstChar = lcfirst($sampleString); echo $lowerFirstChar; ?></td>
        </tr>
        <tr>
            <td>Count Words in String</td>
            <td>str_word_count()</td>
            <td><?php $wordCount = str_word_count($sampleString); echo $wordCount; ?></td>
        </tr>
        <tr>
            <td>Repeat String</td>
            <td>str_repeat()</td>
            <td><?php $repeatedString = str_repeat($sampleString, 3); echo $repeatedString; ?></td>
        </tr>
        <tr>
            <td>Pad String</td>
            <td>str_pad()</td>
            <td><?php $paddedString = str_pad($sampleString, 20, "*"); echo $paddedString; ?></td>
        </tr>
        <tr>
            <td>Substring</td>
            <td>substr()</td>
            <td><?php $substring = substr($sampleString, 3, 5); echo $substring; ?></td>
        </tr>
        <tr>
            <td>ASCII Value of First Character</td>
            <td>ord()</td>
            <td><?php $asciiValue = ord($sampleString); echo $asciiValue; ?></td>
        </tr>
        <tr>
            <td>Compare Strings</td>
            <td>strcmp()</td>
            <td><?php $string1 = "Hello"; $string2 = "Hello"; $comparison = strcmp($string1, $string2); echo $comparison; ?></td>
        </tr>
        <tr>
            <td>Convert to ASCII</td>
            <td>chr()</td>
            <td><?php $asciiString = chr(65); echo $asciiString; ?></td>
        </tr>
        <tr>
            <td>String Shuffle</td>
            <td>str_shuffle()</td>
            <td><?php $shuffledString = str_shuffle($sampleString); echo $shuffledString; ?></td>
        </tr>
        <tr>
            <td>String Repeat</td>
            <td>str_repeat()</td>
            <td><?php $repeatedString = str_repeat($sampleString, 2); echo $repeatedString; ?></td>
        </tr>
        <tr>
            <td>String Comparison (Case-insensitive)</td>
            <td>strcasecmp()</td>
            <td><?php $string1 = "hello"; $string2 = "Hello"; $comparison = strcasecmp($string1, $string2); echo $comparison; ?></td>
        </tr>
        <tr>
            <td>Substring Replace</td>
            <td>substr_replace()</td>
            <td><?php $replacedString = substr_replace($sampleString, "Universe", 7, 5); echo $replacedString; ?></td>
        </tr>
        <tr>
            <td>String Comparison (Natural)</td>
            <td>strnatcmp()</td>
            <td><?php $string1 = "file12.txt"; $string2 = "file2.txt"; $comparison = strnatcmp($string1, $string2); echo $comparison; ?></td>
        </tr>
        <tr>
            <td>Substring Count</td>
            <td>substr_count()</td>
            <td><?php $count = substr_count($sampleString, "l"); echo $count; ?></td>
        </tr>
        <tr>
            <td>String Position (Case-insensitive)</td>
            <td>stripos()</td>
            <td><?php $position = stripos($sampleString, "world"); echo $position; ?></td>
        </tr>
        <tr>
            <td>Substring Replace (Case-insensitive)</td>
            <td>str_ireplace()</td>
            <td><?php $replacedString = str_ireplace("world", "Universe", $sampleString); echo $replacedString; ?></td>
        </tr>
        <tr>
            <td>Case-insensitive String Search</td>
            <td>stristr()</td>
            <td><?php $searchResult = stristr($sampleString, "world"); echo $searchResult; ?></td>
        </tr>
        <tr>
            <td>Split String into Array</td>
            <td>explode()</td>
            <td><?php $splitString = explode(", ", $sampleString); print_r($splitString); ?></td>
        </tr>
        <tr>
            <td>Join Array Elements with String</td>
            <td>implode()</td>
            <td><?php $arrayToJoin = array("Hello", "World!"); $joinedString = implode(" ", $arrayToJoin); echo $joinedString; ?></td>
        </tr>
    </table>


    <!-- ---------------------------------------------- -->

    <h2>Array Functions</h2>
    <table>
        <tr>
            <th>Operation</th>
            <th>Syntax</th>
            <th>Output</th>
        </tr>
        <tr>
            <td>Sorted Array (Ascending Order)</td>
            <td>sort()</td>
            <td>
                <?php 
                    $sampleArray = array(5, 2, 8, 1, 9, 3, 7, 4, 6);
                    sort($sampleArray);
                    echo implode(", ", $sampleArray);
                ?>
            </td>
        </tr>
        <tr>
            <td>Sorted Array (Descending Order)</td>
            <td>rsort()</td>
            <td>
                <?php 
                    rsort($sampleArray);
                    echo implode(", ", $sampleArray);
                ?>
            </td>
        </tr>
        <tr>
            <td>Sorted Array by Values (Maintaining Index Association)</td>
            <td>asort()</td>
            <td>
                <?php 
                    asort($sampleArray);
                    print_r($sampleArray);
                ?>
            </td>
        </tr>
        <tr>
            <td>Sorted Array by Values (Descending Order, Maintaining Index Association)</td>
            <td>arsort()</td>
            <td>
                <?php 
                    arsort($sampleArray);
                    print_r($sampleArray);
                ?>
            </td>
        </tr>
        <tr>
            <td>Sorted Array by Keys</td>
            <td>ksort()</td>
            <td>
                <?php 
                    ksort($sampleArray);
                    print_r($sampleArray);
                ?>
            </td>
        </tr>
        <tr>
            <td>Sorted Array by Keys (Descending Order)</td>
            <td>krsort()</td>
            <td>
                <?php 
                    krsort($sampleArray);
                    print_r($sampleArray);
                ?>
            </td>
        </tr>
        <tr>
            <td>After array_push()</td>
            <td>array_push()</td>
            <td>
                <?php 
                    array_push($sampleArray, 6, 7);
                    print_r($sampleArray);
                ?>
            </td>
        </tr>
        <tr>
            <td>Popped Element</td>
            <td>array_pop()</td>
            <td>
                <?php 
                    $lastElement = array_pop($sampleArray);
                    echo $lastElement;
                ?>
            </td>
        </tr>
        <tr>
            <td>After array_pop()</td>
            <td>array_pop()</td>
            <td>
                <?php 
                    print_r($sampleArray);
                ?>
            </td>
        </tr>
        <tr>
            <td>Shifted Element</td>
            <td>array_shift()</td>
            <td>
                <?php 
                    $firstElement = array_shift($sampleArray);
                    echo $firstElement;
                ?>
            </td>
        </tr>
        <tr>
            <td>After array_shift()</td>
            <td>array_shift()</td>
            <td>
                <?php 
                    print_r($sampleArray);
                ?>
            </td>
        </tr>
        <tr>
            <td>After array_unshift()</td>
            <td>array_unshift()</td>
            <td>
                <?php 
                    array_unshift($sampleArray, 0);
                    print_r($sampleArray);
                ?>
            </td>
        </tr>
        <tr>
            <td>Slice of Array</td>
            <td>array_slice()</td>
            <td>
                <?php 
                    $slice = array_slice($sampleArray, 2, 3);
                    print_r($slice);
                ?>
            </td>
        </tr>
        <tr>
            <td>Merged Array</td>
            <td>array_merge()</td>
            <td>
                <?php 
                    $secondArray = array(6, 7, 8);
                    $mergedArray = array_merge($sampleArray, $secondArray);
                    print_r($mergedArray);
                ?>
            </td>
        </tr>
        <tr>
            <td>Unique Array</td>
            <td>array_unique()</td>
            <td>
                <?php 
                    $dupArray = array(1, 2, 3, 2, 4, 5, 4);
                    $uniqueArray = array_unique($dupArray);
                    print_r($uniqueArray);
                ?>
            </td>
        </tr>
        <tr>
            <td>Flipped Array</td>
            <td>array_flip()</td>
            <td>
                <?php 
                    $flippedArray = array_flip($sampleArray);
                    print_r($flippedArray);
                ?>
            </td>
        </tr>
        <tr>
            <td>Check if Key Exists</td>
            <td>array_key_exists()</td>
            <td>
                <?php 
                    $keyToCheck = 3;
                    if (array_key_exists($keyToCheck, $sampleArray)) {
                        echo "Key $keyToCheck exists in the array.";
                    } else {
                        echo "Key $keyToCheck does not exist in the array.";
                    }
                ?>
            </td>
        </tr>
        <tr>
            <td>Search Value in Array</td>
            <td>array_search()</td>
            <td>
                <?php 
                    $searchValue = 4;
                    $keyFound = array_search($searchValue, $sampleArray);
                    if ($keyFound !== false) {
                        echo "Value $searchValue found at key $keyFound in the array.";
                    } else {
                        echo "Value $searchValue not found in the array.";
                    }
                ?>
            </td>
        </tr>
        <tr>
            <td>Keys of Array</td>
            <td>array_keys()</td>
            <td>
                <?php 
                    $keysArray = array_keys($sampleArray);
                    print_r($keysArray);
                ?>
            </td>
        </tr>
        <tr>
            <td>Values of Array</td>
            <td>array_values()</td>
            <td>
                <?php 
                    $valuesArray = array_values($sampleArray);
                    print_r($valuesArray);
                ?>
            </td>
        </tr>
        <tr>
            <td>Reversed Array</td>
            <td>array_reverse()</td>
            <td>
                <?php 
                    $reversedArray = array_reverse($sampleArray);
                    print_r($reversedArray);
                ?>
            </td>
        </tr>
        <tr>
            <td>Sum of Array</td>
            <td>array_sum()</td>
            <td>
                <?php 
                    $sum = array_sum($sampleArray);
                    echo $sum;
                ?>
            </td>
        </tr>
        <tr>
            <td>Filtered Array (Even Numbers Only)</td>
            <td>filterFunc()</td>
            <td>
                <?php 
                    function filterFunc($value) {
                        return $value % 2 == 0;
                    }
                    $filteredArray = array_filter($sampleArray, "filterFunc");
                    print_r($filteredArray);
                ?>
            </td>
        </tr>
    </table>

    <h2>PHP Code Snippets</h2>
    <table>
        <tr>
            <th>Description</th>
            <th>Syntax</th>
            <th>Output</th>
        </tr>
        <tr>
            <td>Generate a Random Number between 1 and 100</td>
            <td>$randomNumber = rand(1, 100);</td>
            <td><?php $randomNumber = rand(1, 100); echo $randomNumber . "<br>"; ?></td>
        </tr>
        <tr>
            <td>Add Elements to an Array</td>
            <td>$fruits = array("apple", "banana", "cherry");<br>array_push($fruits, "orange", "pear");</td>
            <td><?php 
                $fruits = array("apple", "banana", "cherry");
                array_push($fruits, "orange", "pear");
                echo "Fruits: " . implode(", ", $fruits) . "<br>";
            ?></td>
        </tr>
        <tr>
            <td>Concatenate Strings</td>
            <td>$concatenatedString = "Hello, " . "World!";</td>
            <td><?php $concatenatedString = "Hello, " . "World!"; echo  $concatenatedString . "<br>"; ?></td>
        </tr>
    </table>

</body>
</html>
