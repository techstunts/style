<?php
// Initialize variables to null.
$bodytype_err ="";
$budget_err ="";
$age_err ="";
$occasion_err ="";
$gender_err="";
$stylish_err="";
$lookname_err="";
$lookdescription_err="";
$producname1_err="";
$producttype1_err="";
$productprice1_err="";
$productlink1_err="";
$brand1_err="";
$image1_err="";
$producname2_err="";
$producttype2_err="";
$productprice2_err="";
$productlink2_err="";
$image2_err="";
$brand2_err="";
$producname3_err="";
$producttype3_err="";
$productprice3_err="";
$productlink3_err="";
$image3_err="";
$brand3_err="";
$producname4_err="";
$producttype4_err="";
$productprice4_err="";
$productlink4_err="";
$image4="";
$brand4_err="";

// On submitting form below function will execute.
if(isset($_POST['submit'])){
if (!isset($_POST['bodytype'])) {
$bodytype_err = "Please Select a Bodytype ";

} else {
$body_type = $_POST['bodytype'];
}
if (!isset($_POST['budget'])) {
$budget_err = "Please Select a Budget ";
} else {
$budget = $_POST['budget'];
}
if (!isset($_POST['age'])) {
$age_err = "Please Select a Age ";
} else {
$age = $_POST['age'];
}
if (!isset($_POST['Occasion'])) {
$occasion_err = "Please Select an Occasion ";
} else {
$occasion = $_POST['Ocassion'];
}
if (!isset($_POST['gender'])) {
$gender_err = "Please Select a Gender ";
} else {
$gender = $_POST['gender'];
}
if (!isset($_POST['stylish_name'])) {
$stylishname_err = "Please Select a Stylish Name ";
} else {
$stylish_name = $_POST['stylish_name'];
}
if (empty($_POST["look_name"])) {
$lookname_err = "Look Name is required";
} else {
$look_name = test_input($_POST["look_name"]);
// check name only contains letters and whitespace
if (!preg_match("/^[a-zA-Z ]*$/",$look_name)) {
$lookname_err = "Only letters and white space allowed";
}
}
if (empty($_POST["look_description"])) {
$lookdescription_err= "Look Description is required";
} else {
$look_descrip = test_input($_POST["look_description"]);
}
//Product 1
if (empty($_POST["productname1"])) {
$productname1_err = "Product name is required";
} else {
$productname1 = test_input($_POST["productname1"]);
// check name only contains letters and whitespace
if (!preg_match("/^[a-zA-Z ]*$/",$productname1)) {
$productname1_err = "Only letters and white space allowed";
}
}
if (!isset($_POST['producttype1'])) {
$producttype1_err = "Please Select a Product Type ";
} else {
$producttype1 = $_POST['producttype1'];
}
if (empty($_POST["productprice1"])) {
$productprice1_err = "Product price is required";
} else {
$productprice1 = test_input($_POST["productprice1"]);
// check name only contains letters and whitespace
if (!preg_match("/^[a-zA-Z ]*$/",$productprice1)) {
$productprice1 = "Only letters and white space allowed";
}
}
if (empty($_POST["productlink1"])) {
$productlink1_err = "Product link is required";
} else {
$productlink1 = test_input($_POST["productlink1"]);
// check name only contains letters and whitespace
if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$productlink1)) {
$productlink1_err = "Invalid URL";
}
}
//Product 2

if (empty($_POST["productname2"])) {
$productname2_err = "Product name is required";
} else {
$productname2 = test_input($_POST["productname2"]);
// check name only contains letters and whitespace
if (!preg_match("/^[a-zA-Z ]*$/",$productname2)) {
$productname2_err = "Only letters and white space allowed";
}
}
if (!isset($_POST['producttype2'])) {
$producttype2_err = "Please Select a Product Type ";
} else {
$producttype2 = $_POST['producttype2'];
}
if (empty($_POST["productprice2"])) {
$productprice2_err = "Product price is required";
} else {
$productprice2 = test_input($_POST["productprice2"]);
// check name only contains letters and whitespace
if (!preg_match("/^[a-zA-Z ]*$/",$productprice2)) {
$productprice2 = "Only letters and white space allowed";
}
}
if (empty($_POST["productlink2"])) {
$productlink2_err = "Product link is required";
} else {
$productlink2 = test_input($_POST["productlink2"]);
// check name only contains letters and whitespace
if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$productlink2)) {
$productlink2_err = "Invalid URL";
}
}
//Product 3
if (empty($_POST["productname3"])) {
$productname3_err = "Product name is required";

} else {
$productname3 = test_input($_POST["productname3"]);
// check name only contains letters and whitespace
if (!preg_match("/^[a-zA-Z ]*$/",$productname3)) {
$productname3_err = "Only letters and white space allowed";
}
}
if (!isset($_POST['producttype3'])) {
$producttype3_err = "Please Select a Product Type ";
} else {
$producttype3 = $_POST['producttype3'];
}
if (empty($_POST["productprice3"])) {
$productprice3_err = "Product price is required";
} else {
$productprice3 = test_input($_POST["productprice3"]);
// check name only contains letters and whitespace
if (!preg_match("/^[a-zA-Z ]*$/",$productprice3)) {
$productprice3 = "Only letters and white space allowed";
}
}
if (empty($_POST["productlink3"])) {
$productlink1_err = "Product link is required";
} else {
$productlink3 = test_input($_POST["productlink3"]);
// check name only contains letters and whitespace
if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$productlink3)) {
$productlink3_err = "Invalid URL";
}
}
//Product 4
if (empty($_POST["productname4"])) {
$productname4_err = "Product name is required";
} else {
$productname4 = test_input($_POST["productname4"]);
// check name only contains letters and whitespace
if (!preg_match("/^[a-zA-Z ]*$/",$productname4)) {
$productname4_err = "Only letters and white space allowed";
}
}
if (!isset($_POST['producttype4'])) {
$producttype4_err = "Please Select a Product Type ";
} else {
$producttype4 = $_POST['producttype4'];
}
if (empty($_POST["productprice4"])) {
$productprice4_err = "Product price is required";
} else {
$productprice4 = test_input($_POST["productprice4"]);
// check name only contains letters and whitespace
if (!preg_match("/^[a-zA-Z ]*$/",$productprice4)) {
$productprice4 = "Only letters and white space allowed";
}
}
if (empty($_POST["productlink4"])) {
$productlink4_err = "Product link is required";
} else {
$productlink4 = test_input($_POST["productlink4"]);
// check name only contains letters and whitespace
if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$productlink4)) {
$productlink4_err = "Invalid URL";
}
}

}
function test_input($data) {
$data = trim($data);
$data = stripslashes($data);
$data = htmlspecialchars($data);
return $data;
}

//php code ends here
?>