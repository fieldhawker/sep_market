@extends('layouts.users_layout')

@section('title')
    SEPima! TOP
@stop
        
@section('content')

    <div class="wrapper row1">
        <header id="header" class="clear">
            <!-- ################################################################################################ -->
            <div id="logo" class="fl_left">
                <h1><a href="index.html">SEPima!</a></h1>
            </div>
            <!-- ################################################################################################ -->
            <nav id="mainav" class="fl_right">
                <ul class="clear">
                    <li class="active"><a href="index.html">Home</a></li>
                    <li><a class="drop" href="#">Pages</a>
                        <ul>
                            <li><a href={{asset("colossus/pages/gallery.html")}}>Gallery</a></li>
                            <li><a href={{asset("colossus/pages/full-width.html")}}>Full Width</a></li>
                            <li><a href={{asset("colossus/pages/sidebar-left.html")}}>Sidebar Left</a></li>
                            <li><a href={{asset("colossus/pages/sidebar-right.html")}}>Sidebar Right</a></li>
                            <li><a href={{asset("colossus/pages/basic-grid.html")}}>Basic Grid</a></li>
                        </ul>
                    </li>
                    <li><a class="drop" href="#">Dropdown</a>
                        <ul>
                            <li><a href="#">Level 2</a></li>
                            <li><a class="drop" href="#">Level 2 + Drop</a>
                                <ul>
                                    <li><a href="#">Level 3</a></li>
                                    <li><a href="#">Level 3</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li><a href="#">Link Text</a></li>
                    <li><a href="#">Link Text</a></li>
                </ul>
            </nav>
            <!-- ################################################################################################ -->
        </header>
    </div>
    <!-- ################################################################################################ -->
    <!-- ################################################################################################ -->
    <!-- ################################################################################################ -->
    <div class="wrapper">
        <div id="slider" class="clear">
            <!-- ################################################################################################ -->
            <div class="flexslider basicslider">
                <ul class="slides">
                    <li><img src={{asset("colossus/images/demo/slides/01.png")}} alt="">
                        <div class="txtoverlay">
                            <div class="centralise">
                                <div class="verticalwrap">
                                    <article>
                                        <h2 class="heading uppercase">ivamus commodo mi a lobortis ultrices</h2>
                                        <p><a class="btn orange pushright" href="#">Leo facilisis odio</a> <a class="btn red" href="#">Quis mollis nibh dolor</a></p>
                                    </article>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li><img src={{asset("colossus/images/demo/slides/02.png")}} alt="">
                        <div class="txtoverlay">
                            <div class="centralise">
                                <div class="verticalwrap">
                                    <article>
                                        <h2 class="heading uppercase">Curabitur ullamcorper malesuada tempor</h2>
                                        <p><a class="btn red" href="#">Suspendisse lobortis mauris</a></p>
                                    </article>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li><img src={{asset("colossus/images/demo/slides/03.png")}} alt="">
                        <div class="txtoverlay">
                            <div class="centralise">
                                <div class="verticalwrap">
                                    <article>
                                        <h2 class="heading uppercase">Fusce in nisi auctor imperdiet quam quis</h2>
                                        <p><a class="btn orange pushright" href="#">Integer posuere arcu nec</a> <a class="btn red" href="#">Odio sollicitudin sagittis</a></p>
                                    </article>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <!-- ################################################################################################ -->
        </div>
    </div>
    <!-- ################################################################################################ -->
    <!-- ################################################################################################ -->
    <!-- ################################################################################################ -->
    <div class="wrapper row2">
        <div id="services" class="clear">
            <!-- ################################################################################################ -->
            <div class="group">
                <div class="one_third first">
                    <article class="service"><i class="icon red circle fa fa-bell-o"></i>
                        <h2 class="heading">Phasellus accumsan velit lacus</h2>
                        <p class="btmspace-10">Ut vitae mi turpis donec convallis turpis bibendum dolor hendrerit eget ultrices.</p>
                        <p><a href="#">Read More &raquo;</a></p>
                    </article>
                </div>
                <div class="one_third">
                    <article class="service"><i class="icon orange circle fa fa-bicycle"></i>
                        <h2 class="heading">Duis in dictum erat phasellus cursus</h2>
                        <p class="btmspace-10">Ut augue ante euismod vitae scelerisque non tincidunt ut velit integer et iaculis.</p>
                        <p><a href="#">Read More &raquo;</a></p>
                    </article>
                </div>
                <div class="one_third">
                    <article class="service"><i class="icon green circle fa fa-mortar-board"></i>
                        <h2 class="heading">Vivamus accumsan mollis mi in ultricies</h2>
                        <p class="btmspace-10">Nullam commodo orci ut justo bibendum tristique proin vel est at risus volutpat.</p>
                        <p><a href="#">Read More &raquo;</a></p>
                    </article>
                </div>
            </div>
            <!-- ################################################################################################ -->
            <div class="clear"></div>
        </div>
    </div>
    <!-- ################################################################################################ -->
    <!-- ################################################################################################ -->
    <!-- ################################################################################################ -->
    <div class="wrapper row6">
        <section id="cta" class="clear">
            <!-- ################################################################################################ -->
            <div class="three_quarter first">
                <h2 class="heading">Fusce quis feugiat urna dui leo egestas augue</h2>
                <p>Aenean semper elementum tellus, ut placerat leo. Quisque vehicula, urna sit amet pulvinar dapibus.</p>
            </div>
            <div class="one_quarter"><a class="btn" href="#">Get it now <span class="fa fa-arrow-right"></span></a></div>
            <!-- ################################################################################################ -->
        </section>
    </div>
    <!-- ################################################################################################ -->
    <!-- ################################################################################################ -->
    <!-- ################################################################################################ -->
    <div class="wrapper row2">
        <div class="latest">
            <!-- ################################################################################################ -->
            <ul class="nospace group">
                <li>
                    <figure><a class="overlay" href="#"><img src={{asset("colossus/images/demo/latest/01.png")}} alt=""></a>
                        <figcaption class="inspace-30 center">
                            <p class="bold uppercase">Ante lectus laoreet</p>
                            <p><a href="#">View Here &raquo;</a></p>
                        </figcaption>
                    </figure>
                </li>
                <li>
                    <figure><a class="overlay" href="#"><img src={{asset("colossus/images/demo/latest/01.png")}} alt=""></a>
                        <figcaption class="inspace-30 center">
                            <p class="bold uppercase">Non facilisis tellus</p>
                            <p><a href="#">View Here &raquo;</a></p>
                        </figcaption>
                    </figure>
                </li>
                <li>
                    <figure><a class="overlay" href="#"><img src={{asset("colossus/images/demo/latest/01.png")}} alt=""></a>
                        <figcaption class="inspace-30 center">
                            <p class="bold uppercase">In egestas tincidunt</p>
                            <p><a href="#">View Here &raquo;</a></p>
                        </figcaption>
                    </figure>
                </li>
                <li>
                    <figure><a class="overlay" href="#"><img src={{asset("colossus/images/demo/latest/01.png")}} alt=""></a>
                        <figcaption class="inspace-30 center">
                            <p class="bold uppercase">Integer ullamcorper</p>
                            <p><a href="#">View Here &raquo;</a></p>
                        </figcaption>
                    </figure>
                </li>
                <li>
                    <figure><a class="overlay" href="#"><img src={{asset("colossus/images/demo/latest/01.png")}} alt=""></a>
                        <figcaption class="inspace-30 center">
                            <p class="bold uppercase">Cras lectus pulvinar</p>
                            <p><a href="#">View Here &raquo;</a></p>
                        </figcaption>
                    </figure>
                </li>
            </ul>
            <!-- ################################################################################################ -->
        </div>
    </div>
    <!-- ################################################################################################ -->
    <!-- ################################################################################################ -->
    <!-- ################################################################################################ -->
    <div class="wrapper row3">
        <main class="container nospace clear">
            <!-- main body -->
            <!-- ################################################################################################ -->
            <div class="group">
                <div class="one_half first paditout">
                    <h2 class="heading uppercase btmspace-50">Integer varius enim id augue faucibus mattis</h2>
                    <ul class="nospace group">
                        <li class="btmspace-30">
                            <article class="service largeicon"><i class="icon nobg circle fa fa-ra"></i>
                                <h6 class="heading"><a href="#">Vestibulum nibh enim</a></h6>
                                <p>Aenean semper elementum tellus, ut placerat leo. Quisque vehicula, urna sit amet.</p>
                            </article>
                        </li>
                        <li class="btmspace-30">
                            <article class="service largeicon"><i class="icon nobg circle fa fa-female"></i>
                                <h6 class="heading"><a href="#">Praesent facilisis diam</a></h6>
                                <p>Aenean semper elementum tellus, ut placerat leo. Quisque vehicula, urna sit amet.</p>
                            </article>
                        </li>
                        <li>
                            <article class="service largeicon"><i class="icon nobg circle fa fa-history"></i>
                                <h6 class="heading"><a href="#">Proin ac augue sed nulla</a></h6>
                                <p>Aenean semper elementum tellus, ut placerat leo. Quisque vehicula, urna sit amet.</p>
                            </article>
                        </li>
                    </ul>
                </div>
                <div class="one_half"><a href="#"><img src={{asset("colossus/images/demo/vertical.png")}} alt=""></a></div>
            </div>
            <!-- ################################################################################################ -->
            <!-- / main body -->
            <div class="clear"></div>
        </main>
    </div>

@endsection