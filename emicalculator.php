<?php 

error_reporting(0); 
$rate = $_POST['interest']/100/12;
$principle = $_POST['principal'];
$time = $_POST['years']*12;// in month
$x= pow(1+$rate,$time);
//echo $x;
$monthly = ($principle*$x*$rate)/($x-1);
$monthly = round($monthly);
$k= $time;
$arr= array();
function getNextMonth($date){
    global $arr;
    global $k;
    if($k==0){
        return 0;	
    }
    $date = new DateTime($date);
    $interval = new DateInterval('P1M');
    $date->add($interval);
    $nextMonth= $date->format('Y-m-d') . "\n";	
    $arr[]= $nextMonth;
    $k--;
    return getNextMonth($nextMonth);
}
getNextMonth($_POST['start_date']);
// simple chart display here
$date = "";
$upto = $time;
$i = 0;
$totalint = 0;
$payment_date = date("Y m,d");
$tp =0;
function getEmi($t){
    global $i,$upto, $totalint, $rate,$monthly,$payment_date, $arr,$_SESSION,$tp;
    $i++;
    if($upto<=0){
        return 0;
    }
    $r = $t*$rate;
    $p = round($monthly-$r);
    $e= round($t-$p);
    if($upto==2){
        $_SESSION['tl']= $e;
    }
    if($upto==1){
        $p= $_SESSION['tl'];	
        $e= round($t-$p);
        $monthly= round($p+$r);
    }
    $totalint = $totalint + $r;
    $tp = $tp+$monthly;
    $upto--;
?>
<tr>
    <td>
        <?php echo $i; ?></td>
    <td>
        <?php
    $arrDate1 = explode('-',$arr[$i-1]);
    echo date("M j, Y",mktime(0,0,0,$arrDate1[1],$arrDate1[2],$arrDate1[0]));
        ?></td>
    <td>       
        ₹<?php echo number_format(round($r)); ?>.00
    </td>
    <td>
        ₹<?php  echo number_format($t); ?>.00
    </td>
    <td>
        ₹<?php echo number_format($p);  ?>.00
    </td>
    <td>       
        ₹<?php echo number_format($monthly); ?>.00
    </td>
    <td>        
        ₹<?php echo number_format(round($e));  ?>.00
    </td>
</tr>
<?php
    return getEmi($e);
}
?>
</style>
<form name="loandata" method="post" action="">
    <table id="emi" width="100%">
        <tr >
            <td colspan="3" class="loan-title">
                <b>
                    Enter Loan Information:
                </b>
            </td>
        </tr>
        <tr>
            <td width="50%">
                <p class="line-p">Amount of the loan (Any currency):
                <span class="err">*</span></p>
            </td>
            <td>
                <input type="number" name="principal" size="12" placeholder="Enter Amount" required>
            </td>
        </tr>
        <tr>
            <td>
				<p class="line-p">Annual percentage rate of interest:
                <span class="err">*</span></p>
            </td>
            <td>
                <input type="text" name="interest" size="12" placeholder="Enter Interest" required>
            </td>
        </tr>
        <tr>
            <td>
				<p class="line-p">Repayment period in years:
                <span class="err">*</span></p>
            </td>
            <td>
                <input type="number" name="years" size="12" placeholder="Enter Years" required> 
            </td>
        </tr>
        <tr>
            <td>
                <p class="line-p">Start Date of Loan:</p>
            </td>
            <td>
                <input type="text" name="start_date" size="12" id="start_date" placeholder="Enter Date">
            </td>
        </tr>
        <tr>
            <td colspan="3" class="loan-btn">
                <input type="submit" value="Calculate Loan"  name="EMI_submit" class="btn btn-primary">
            </td>
        </tr>
    </table>
</form>
<style type="text/css">
    
</style>
<table cellpadding="0" cellspacing="0" width="100%" class="eni_list">
    <?php 
if(!empty($_POST['principal']) || !empty($_POST['interest']) || !empty($_POST['years'])){
    if(empty($_POST['principal'])){
        $error = "Amount of the loan Cant't Be Empty.<br />";
    }
    else if(empty($_POST['interest'])){
        $error= "Annual percentage rate of interest Cant't Be Empty. <br />";
    }
    else if(empty($_POST['years'])){
        $error= "Repayment period in years Cant't Be Empty. <br />";
    }
    else {
        //simple chart dispaly here 
    ?>
<tr>
	<td colspan="7">
		<table id="result" width="100%">
			<tr>
				<td colspan="3">
					<b class="line-head">Payment Information</b>
				</td>
			</tr>
            <tr>
				<td>
					<p class="line-p">Your monthly payment will be:</p>
                </td>
                <td>
					<span id="monthly">₹<?php echo round($monthly); ?>.00</span>
                </td>
            </tr>
            <tr>
                <td>
                    <p class="line-p">Your total payment will be:</p>
                </td>
				<td>
                    <span id="total"></span>
                </td>
            </tr>
            <tr>
                <td>
					<p class="line-p">Your total interest payments will be:</p>
                </td>
				<td>
					<span id="interest"></span>
                </td>
            </tr>
        </table>
    </td>
</tr>
	
<tr>
    <td colspan="12">
		<b class="line-head">Final Result</b>
    </td>
</tr>
<tr>
    <td>
        <p class="line-p">S.N</p>
    </td>
    <td>
		<p class="line-p">Payment Date</p>
    </td>
    <td>
		<p class="line-p">Interest</p>
    </td>
    <td>
		<p class="line-p">Beginning Balance</p>
    </td>
    <td>
        <p class="line-p">Principle</p>
    </td>
    <td>
        <p class="line-p">Total Payment</p>
    </td>
    <td>
        <p class="line-p">Ending Balance</p>
    </td>
</tr>
    <?php
        getEmi($_POST['principal']); 
    ?>
    <script type="text/ecmascript">
        document.getElementById("interest").innerHTML="$"+<?php echo round($totalint); ?>+".00";
        document.getElementById("total").innerHTML="$"+<?php echo round($tp); ?>+".00";
    </script>
    <?php
    }}
else {
    $error= "Plese Fill Up All Required Fields.";	
}
    ?>
    <?php if(!empty($error)) : ?>
    <tr>
        <td colspan="6" style="color:#F00; font-size:18px;">
            <?php echo $error; ?></td>
    </tr>
    <?php endif; ?>
</table>
<?php if(isset($_POST['EMI_submit'])){ ?>
<script language="JavaScript">
    document.getElementById('result').style.display='block';
</script>
<?php } ?>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="./css/style.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
    var j =  jQuery.noConflict();
    j(function(){
        // Datepicker
        j('#start_date').datepicker({
            inline: true,
            minDate: "today"
        })
        });
</script>
