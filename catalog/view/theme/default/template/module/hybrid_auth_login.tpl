<?php if (!strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 6')) echo '<?xml version="1.0" encoding="UTF-8"?>'. "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" xml:lang="<?php echo $lang; ?>">
<head>
<title><?php echo $provider ?> Login Page</title>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/stylesheet.css" />
<style>
#login {
margin:10px auto 0 auto;
width:400px;
background:#fff;
padding:10px;
}
#login img{
  border:1px solid #ddd;
  margin: 0 10px 0 0;
}
.email_input{
  clear: both;
  margin:10px 0 0 0;
}
label {
    display:block;
}
input {
  width:390px;
  border:1px solid #333;
  padding:2px;
  margin:0 0 10px 0;
}

</style>
</head>
<body>
  <div id="container">
      <div id="logo">
      <?php if($logo): ?>
        <a href="<?php echo $site_url ?>">
            <img src="<?php echo $logo ?>" />
        </a>
        <?php endif; ?>
      </div>
      <form name="login" action="<?php echo $action ?>" method="post">
      <input type="hidden" name="user[firstname]" value="<?php echo $user['firstName'] ?>" />
      <input type="hidden" name="user[displayname]" value="<?php echo $user['displayName'] ?>" />
      <input type="hidden" name="user[lastname]" value="<?php echo $user['lastName'] ?>" />
      <div id="login">
        <img src="<?php echo $user['photoURL']; ?>" alt="<?php echo $user['displayName'] ?>" align="left" width="75" height="75" />
        <div class="profile">
             <h1><a href="<?php echo $user['profileUrl'] ?>"><?php echo $user['displayName']; ?></a></h1>
             <h2><?php echo $user['firstName'] ?> <?php echo $user['lastName'] ?></h2>
        </div>
        <div class='email_input'>
            <label for="email">Enter your email to sign up on The Mexican Pharma</label>
            <input type="text" id="email" name="user[email]" value="" />
            <br />
            <div class="right">
            <a href="javascript:void(0)" onclick="validateForm()" class="button"><span>Sign up</span></a>
            <a href="<?php echo $login ?>" class="button"><span>Cancel</span></a>
            </div>
        </div>
      </div>
      </form>
  </div>
  <script type="text/javascript">
  function validateEmail(email) {
    var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return re.test(email);
  }
  function validateForm(){
    var email = document.getElementById('email');
    if(email.value == '' || !validateEmail(email.value)){
        alert('Email field is empty or you type invalid email!');
        email.focus();
    } else {
      document.login.submit();
    }
  }
  </script>
</body>
</html>