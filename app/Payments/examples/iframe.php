<!DOCTYPE HTML>
<HTML>
    <HEAD>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8; width=device-width; initial-scale=1.0"> 
        <TITLE>Test iframe - PHP:</TITLE>
    </HEAD>
    <BODY style="background-color:#CCAACC" >
        <div style="text-align: center">
            <P><h1>Test iframe - PHP:</h1></P>

            <form id="moodleform" target="myIframe" method="post" action="<?php echo($_POST['TranType']. '.php'); ?>" >
                <input type="hidden" name="Language" value="<?php echo($_POST["Language"]); ?>"/>
                <input type="hidden" name="Amount" value="<?php echo($_POST["Amount"]); ?>"/>
                <input type="hidden" name="Currency" value="<?php echo($_POST["Currency"]); ?>"/>
                <input type="hidden" name="numInstalment" value="<?php echo($_POST["numInstalment"]); ?>"/>
                <input type="hidden" name="initialStoreTrans" value="<?php echo($_POST["initialStoreTrans"]); ?>"/>
                <input type="hidden" name="subSeqentTrans" value="<?php echo($_POST["subSeqentTrans"]); ?>"/>
                <input type="hidden" name="gatewaySchadule" value="<?php echo($_POST["gatewaySchadule"]); ?>"/>
                <input type="hidden" name="scheduleUnit" value="<?php echo($_POST["scheduleUnit"]); ?>"/>
                <input type="hidden" name="schedulePeriod" value="<?php echo($_POST["schedulePeriod"]); ?>"/>
                <input type="hidden" name="scheduleDelay" value="<?php echo($_POST["scheduleDelay"]); ?>"/>
                <input type="hidden" name="refTranId" value="<?php echo($_POST["refTranId"]); ?>"/>
                <input type="hidden" name="first_name" value="<?php echo($_POST["first_name"]); ?>"/>
                <input type="hidden" name="last_name" value="<?php echo($_POST["last_name"]); ?>"/> 
                <input type="hidden" name="email" value="<?php echo($_POST["email"]); ?>"/>    
            </form>

            <iframe width="800px" height="750px"  frameborder="yes" scrolling="yes" name="myIframe" id="myIframe" style="background: red"> </iframe>

            <script type="text/javascript">
                document.getElementById('moodleform').submit();
            </script>
        </div>
    </BODY>
</HTML>