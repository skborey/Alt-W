<?php include "header.php"; ?>
</head>
<?php include "menu_header.php"; ?>
    <div class="container">
        <div class="row margin-vert-30">
            <div class="col-md-12">
                <img src="assets/img/portfolio/public.png" alt="About Us" style="display: block; margin: 0 auto;">
                <p></p>
                <ul class="breadcrumb">  <br>  
                    <p></p>
                    <div class="row">
                        <center>
                            <div class="col-md-12 col-lg-offset-2">
                                <div class="shadow">
                                    <div id="carousel-example-1" class="carousel slide" data-ride="carousel">
                                        <div class="carousel-inner">
                                            <div class="item active">
                                                <div class="tiles">
                                                    <div class="tile" data-scale="1.1" data-image="http://i68.tinypic.com/wancjq.jpg"> 
                                                    </div>
                                                </div>
                                                <div class="desc">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">       
                                <br>
                                <center>
                                    <img class="shadow" src="assets/images/1.jpg" alt="wordnet" height="180" width="">&nbsp;&nbsp;  
                                    <img class="shadow" src="assets/images/3.jpg" alt="wordnet" height="180" width="">&nbsp;&nbsp;  
                                    <img class="shadow" src="assets/images/4.jpg" alt="wordnet" height="180" width="">
                                </center>
                                <p></p><br>
                            </div>
                            <div class="col-md-12">       
                                <p></p><br>
                                <h3 class="MsoNormal">
                                    <strong>National Conference on Information Technology (NCIT) 9th</strong>
                                </h3>
                                <p></p>
                                <center>
                                    <h2> 
                                        <img src="assets/images/Book.gif" alt="" />
                                        <a style="color: red; font-size: 20px;" target="_blank" 
                                           href="https://drive.google.com/file/d/1lbA9qTTFGoiZl2TN4u8VOeBFnJ_afswo/view?usp=sharing">
                                            <strong>click to read the paper.&nbsp;</strong>
                                        </a>
                                    </h2> 
                                </center>
                            </div>
                        </center>
                    </div>
                    <hr class="margin-vert-40">
                    <div class="container">            
                        <ul class="pagination pagination-lg" style="float: right">
                            <li class="active"><a href="publication_NCIT.php" style="color: #ccc">NCIT</a></li>
                            <li style="display: none"><a href="publication_NSC.php">NSC</a></li>     
                        </ul>
                    </div> 
                </ul>
            </div>
        </div>
    </div>
    <?php include "menu_footer.php"; ?>
    <?php include "footer.php"; ?>
    <script>
        $('.tile').on('mouseout', function () {
            $(this).children('.photo').css({'transform': 'scale(' + $(this).attr('data-scale') + ')'});
        }).on('mouseover', function () {
            $(this).children('.photo').css({'transform': 'scale(1)'});
        }).each(function () {
            $(this).append('<div class="photo"></div>').append($(this).attr('data-scale')).children('.photo').css({'background-image': 'url(' + $(this).attr('data-image') + ')'});
        })
    </script>
    </body>
</html>