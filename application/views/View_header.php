<html>
    <head>
        <title>Núcleo de Tecnologias Educacionais - UFABC</title>

        <!--<meta name="viewport" content="width=device-width, initial-scale=1">-->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

        <script>
            $(document).ready(function () {
                $('[name=solic_select]').change(function () {
                    var color = $(this).val();
                    if (color == "red") {
                        $(".box").not(".red").hide();
                        $(".red").show();
                    } else if (color == "green") {
                        $(".box").not(".green").hide();
                        $(".green").show();
                    } else if (color == "blue") {
                        $(".box").not(".blue").hide();
                        $(".blue").show();
                    } else if (color == "maroon") {
                        $(".box").not(".maroon").hide();
                        $(".maroon").show();
                    } else if (color == "magenta") {
                        $(".box").not(".magenta").hide();
                        $(".magenta").show();
                    } else {
                        $(".box").hide();
                    }
                });
            });

            $(document).ready(function () {
                $('[name=tutor_ou_docente]').change(function () {
                    var color = $(this).val();
                    if (color == "tutor") {
                        $(".caixa").not(".tutor").hide();
                        $(".tutor").show();
                    } else if (color == "docente") {
                        $(".caixa").not(".docente").hide();
                        $(".docente").show();
                    } else {
                        $(".caixa").hide();
                    }
                });
            });
        </script>


        <link rel="stylesheet" href="/adm-netel/css/style.css" type='text/css'/>

    </head>
    <body>
        <div id = "main" align="center">