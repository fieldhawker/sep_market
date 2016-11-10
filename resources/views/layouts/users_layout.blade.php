<!DOCTYPE html>
<!--
Template Name: Colossus
Author: <a href="http://www.os-templates.com/">OS Templates</a>
Author URI: http://www.os-templates.com/
Licence: Free to use under our free template licence terms
Licence URI: http://www.os-templates.com/template-terms
-->
<html>

@include('layouts.users_header')

<body id="top">
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->

<div class="wrapper row1">
    <header id="header" class="clear">
        <!-- ################################################################################################ -->
        <div id="logo" class="fl_left">
            <h1><a href="#">SEPima!</a></h1>
        </div>
        <!-- ################################################################################################ -->
        <nav id="mainav" class="fl_right">
            <ul class="clear">
                <li class="active"><a href="#">Home</a></li>
                <li><a class="drop" href="#">Pages</a>
                    <ul>
                        <li><a href="{{ url('/users/register') }}">会員登録</a></li>
                        <li><a href={{secure_asset("colossus/pages/full-width.html")}}>Full Width</a></li>
                        <li><a href={{secure_asset("colossus/pages/sidebar-left.html")}}>Sidebar Left</a></li>
                        <li><a href={{secure_asset("colossus/pages/sidebar-right.html")}}>Sidebar Right</a></li>
                        <li><a href={{secure_asset("colossus/pages/basic-grid.html")}}>Basic Grid</a></li>
                    </ul>
                </li>
                <li><a href="{{ url('/admin') }}">管理画面</a></li>
                @if (Auth::guard('users')->guest())
                    <li><a href="{{ url('/users/login') }}">ログイン</a></li>
                @else
                    <li><a class="drop" href="#">{{ Auth::guard("users")->user()->name }}</a>
                        <ul>
                            <li><a href="{{ url('/users/logout') }}">ログアウト</a></li>
                            <li><a class="drop" href="#">Level 2 + Drop</a>
                                <ul>
                                    <li><a href="#">Level 3</a></li>
                                    <li><a href="#">Level 3</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                @endif
                
            </ul>
        </nav>
        <!-- ################################################################################################ -->
    </header>
</div>
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->

@yield('content')
        
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<div class="wrapper row4">
    <footer id="footer" class="clear">
        <!-- ################################################################################################ -->
        <div class="one_quarter first">
            <h6 class="title">Company Details</h6>
            <address class="btmspace-15">
                Company Name<br>
                Street Name &amp; Number<br>
                Town<br>
                Postcode/Zip
            </address>
            <ul class="nospace">
                <li class="btmspace-10"><span class="fa fa-phone"></span> +00 (123) 456 7890</li>
                <li><span class="fa fa-envelope-o"></span> info@domain.com</li>
            </ul>
        </div>
        <div class="one_quarter">
            <h6 class="title">Quick Links</h6>
            <ul class="nospace linklist">
                <li><a href="#">Home Page</a></li>
                <li><a href="#">Blog</a></li>
                <li><a href="#">Gallery</a></li>
                <li><a href="#">Portfolio</a></li>
                <li><a href="#">Contact Us</a></li>
            </ul>
        </div>
        <div class="one_quarter">
            <h6 class="title">From The Blog</h6>
            <article>
                <h2 class="nospace"><a href="#">Lorem ipsum dolor</a></h2>
                <time class="smallfont" datetime="2045-04-06">Friday, 6<sup>th</sup> April 2045</time>
                <p>Vestibulumaccumsan egestibulum eu justo convallis augue estas aenean elit intesque sed.</p>
            </article>
        </div>
        <div class="one_quarter">
            <h6 class="title">Keep In Touch</h6>
            <form class="btmspace-30" method="post" action="#">
                <fieldset>
                    <legend>Newsletter:</legend>
                    <input class="btmspace-15" type="text" value="" placeholder="Email">
                    <button type="submit" value="submit">Submit</button>
                </fieldset>
            </form>
            <ul class="faico clear">
                <li><a class="faicon-facebook" href="#"><i class="fa fa-facebook"></i></a></li>
                <li><a class="faicon-twitter" href="#"><i class="fa fa-twitter"></i></a></li>
                <li><a class="faicon-linkedin" href="#"><i class="fa fa-linkedin"></i></a></li>
                <li><a class="faicon-google-plus" href="#"><i class="fa fa-google-plus"></i></a></li>
                <li><a class="faicon-instagram" href="#"><i class="fa fa-instagram"></i></a></li>
                <li><a class="faicon-tumblr" href="#"><i class="fa fa-tumblr"></i></a></li>
            </ul>
        </div>
        <!-- ################################################################################################ -->
    </footer>
</div>
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<div class="wrapper row5">
    <div id="copyright" class="clear">
        <!-- ################################################################################################ -->
        <p class="fl_left">Copyright &copy; 2015 - All Rights Reserved - <a href="#">Domain Name</a></p>
        <p class="fl_right">Template by <a target="_blank" href="http://www.os-templates.com/" title="Free Website Templates">OS Templates</a></p>
        <!-- ################################################################################################ -->
    </div>
</div>
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<a id="backtotop" href="#top"><i class="fa fa-chevron-up"></i></a>
<!-- JAVASCRIPTS -->
<script src={{secure_asset("colossus/layout/scripts/jquery.min.js")}}></script>
<script src={{secure_asset("colossus/layout/scripts/jquery.backtotop.js")}}></script>
<script src={{secure_asset("colossus/layout/scripts/jquery.mobilemenu.js")}}></script>
<script src={{secure_asset("colossus/layout/scripts/jquery.flexslider-min.js")}}></script>
</body>
</html>
