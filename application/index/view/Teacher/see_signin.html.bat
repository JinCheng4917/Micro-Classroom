<!DOCTYPE html>
<!-- 查看学生当堂课的签到信息 -->
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
        <title>查看当堂签到信息</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <meta name="description" content="Animated 3D Bar Chart with CSS3" />
        <meta name="keywords" content="css3, bar chart, animation, 3d" />
        <meta name="author" content="Sergey Lukin for Codrops" />
        <link rel="shortcut icon" href="../favicon.ico"> 
        <link rel="stylesheet" type="text/css" href="css/demo.css" />
        <link rel="stylesheet" type="text/css" href="css/graph.css" />
    <!--[if lt IE 9]>
      <script type="text/javascript" src="js/modernizr.custom.04022.js"></script> 
      <style>.ie-note-1{display:block;} .main{display:none;}</style>
    <![endif]-->
    <!--[if IE 9]><style>.ie-note-2{display:block;}</style><![endif]-->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.staticfile.org/foundation/5.5.3/css/foundation.min.css">
  <script src="https://cdn.staticfile.org/jquery/2.1.1/jquery.min.js"></script>
  <script src="https://cdn.staticfile.org/foundation/5.5.3/js/foundation.min.js"></script>
  <script src="https://cdn.staticfile.org/foundation/5.5.3/js/vendor/modernizr.js"></script>
  
    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/landing-page.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">
<head>
    <title>查看签到信息</title>
</head>

<body style="background-image: url(img/intro-bg.jpg)">
    
               <!-- Navigation -->
    <nav class="navbar navbar-default navbar-fixed-top topnav" role="navigation">
        <div class="container topnav">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">微课堂</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand topnav" href="#">微课堂1</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li>
                            <a href="{:url('Teacher/index')}" style="font-size: 14px;"><i class="glyphicon glyphicon-share-alt"></i>&nbsp;返回</a>
                        </li>
                    <li>
                        <a href="{:url('Teacher/seeSignin')}" style="font-size: 14px;"><i class="glyphicon glyphicon-repeat"></i>&nbsp;刷新</a>
                    </li>
                    <li>
                    <!--两个注销--TeacherLogout--StudentLogout-->
                        <a href="{:url('Teacher/index')}" style="font-size: 14px;"><i class="glyphicon glyphicon-off"></i>&nbsp;退出</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>
   
    
        <div class="container" style="margin-top: 5%; background-color: white;">
<div style="text-align:center;clear:both;">
<script src="/gg_bd_ad_720x90.js" type="text/javascript"></script>
<script src="/follow.js" type="text/javascript"></script>
</div>


            <section class="main">
        
        <span class="button-label">Size:</span>
                <input type="radio" name="resize-graph" id="graph-small" /><label for="graph-small">Small</label>
                <input type="radio" name="resize-graph" id="graph-normal" checked="checked" /><label for="graph-normal">Normal</label>
                <input type="radio" name="resize-graph" id="graph-large" /><label for="graph-large">Large</label>   

        <span class="button-label">Color:</span>
                <input type="radio" name="paint-graph" id="graph-blue" checked="checked" /><label for="graph-blue">Blue</label>
                <input type="radio" name="paint-graph" id="graph-green" /><label for="graph-green">Green</label>
                <input type="radio" name="paint-graph" id="graph-rainbow" /><label for="graph-rainbow">Rainbow</label>

        <span class="button-label">Product:</span>
                <input type="radio" name="fill-graph" id="f-none" /><label for="f-none">None</label>
                <input type="radio" name="fill-graph" id="f-product1" checked="checked" /><label for="f-product1">Product 1</label>
                <input type="radio" name="fill-graph" id="f-product2" /><label for="f-product2">Product 2</label>
                <input type="radio" name="fill-graph" id="f-product3" /><label for="f-product3">Product 3</label>

           


                <ul class="graph-container">
                    {volist name="$KlassSignIns" id="_SignIn" key="key"}
                    <li>
                        <span>{$_SignIn->getData('klass_name')}</span>
                        <div class="bar-wrapper">
                            <div class="bar-container">
                                <div class="bar-background"></div>
                                <div class="bar-inner" style="height: {$_SignIn->getProportion()}" ></div>
                                <div class="bar-foreground"></div>
                            </div>
                        </div>
                    </li>

                    {/volist}

     

                        <ul class="graph-marker-container">

                            <li style="bottom:25%;"><span>25%</span></li>
                            <li style="bottom:50%;"><span>50%</span></li>
                            <li style="bottom:75%;"><span>75%</span></li>
                            <li style="bottom:100%;"><span>100%</span></li>
                        </ul>
                    </li>
                </ul>

            </section>
            <div class="small-text-center" >

  <a href="{:url('Teacher/getThis')}"><button type="button" style="color: white;">查看未签到人员信息</button></a>
        </div>
</div>
    </body>
</html>
