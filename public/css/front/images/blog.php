<?php include("header4.php"); ?>

<?php
include("pte.php");

$titles = new pte();
$Titles = $titles->GetTitles();

if($Titles==0)
	die("Access denied : Something is wrong");

$ErrorMessage="";

if(isset($_POST["save"])) 
{
	$obj = new pte();
	$res = $obj->GetPaymentRequest($_POST);
	
	if(isset($res["message"]))
		$ErrorMessage=$res["message"];
}
?>


<div class="inner-banner-blog-section"> 
   		<div class="container">
        	<div class="row">
            	<div class="punch-line"><a href="index.php"><h1>BUY PTE VOUCHER CODE NOW &#8377 <?php echo $Titles["Rate"];?> </h1></a></div>
            </div>
        </div>
   </div>
   <div class="inner-policy-content">
   		<div class="container">
		    <h1>Blogs</h1> 
        	<div class="row">
            	<div class="col-md-3">
                	<div class="blog-img" data-toggle="tooltip" title="PTE (Pearson Test of English) Pros and Cons">
						<img src="images/pte-pros-and-cons2.jpeg" class="img-responsive" />
                    </div>
                </div>
				<div class="col-md-9">
                                     <div class="blog-cnt" style="border-bottom:1px solid #ccc;">
					<h3><a href="pte-pros-cons.php">PTE (Pearson Test of English) Pros and Cons</a></h3>
					<p>PTE stands for Pearson Test of English a computer-based English language test that is designed in six different levels such as A1, 1, 2, 3, 4, and 5 for the non-native English speakers who are seeking to study in abroad universities or to work over there.</p>
					<p><a href="pte-pros-cons.php">READ MORE</a></p>
                                        
                                      </div>
				</div>
            </div>
			<div class="row">
            	<div class="col-md-3">
                	<div class="blog-img" data-toggle="tooltip" title="PTE – 10 Things you must know about this exam!">
						<img src="images/blog1.png" class="img-responsive" />
                    </div>
                </div>
				<div class="col-md-9">
                                     <div class="blog-cnt" style="border-bottom:1px solid #ccc;">
					<h3><a href="know-exam.php">PTE – 10 Things you must know about this exam!</a></h3>
					<p>Pearson’s Test of English (PTE) is a well-known English proficiency test for those who are non-native English speakers and want to study abroad. Are you unaware of PTE Exam? Don’t you know what it is for? Are you unaware of the process of buying PTE Voucher? Don’t you have any clue of fees and discounts on PTE Voucher?</p>
					<p><a href="know-exam.php">READ MORE</a></p>
                                        
                                      </div>
				</div>
            </div>
			<div class="row">
            	<div class="col-md-3">
                	<div class="blog-img" data-toggle="tooltip" title="PTE Exam: Registration, Fee, Discounts, Eligibility And More!">
						<img src="images/blog2.png" class="img-responsive" />
                    </div>
                </div>
				<div class="col-md-9"> 
                                       <div class="blog-cnt" style="border-bottom:1px solid #ccc;">
					<h3><a href="exam-reg.php">PTE Exam: Registration, Fee, Discounts, Eligibility And More!</a></h3>
					<p>PTE is an English proficiency test that has been approved by several universities, colleges as well as Governments across the globe. The PTE academic is mainly used for applying in the universities of the foreign countries like USA, Canada, Australia and New Zealand. The exam mainly tests the English communication skills of the non-native English speakers, applying in foreign universities or colleges for higher studies or for visa applications.</p>
					<p><a href="exam-reg.php">READ MORE</a></p>
                                         
                                       </div>
				</div>
            </div>
			
			
        </div>
   </div>
   
<?php include("footer.php"); ?>