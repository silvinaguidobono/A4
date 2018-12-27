<?php
include 'head_common1.php';
?>
<body>
    <div class="jumbotron text-center">
        <h1>TODO</h1>
    </div>
    <div class="container">
        <form class="form-horizontal" method="post" action="home/login">
            <div class="form-group-row">
                <label form="emai">Email</label>
            </div>
        </form>
    </div>
    <h1><?= $this->page ;?></h1> 

<?php
    include 'footer_common.php';
    ?>