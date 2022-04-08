<?php

/* Settings */
$API_Key="4ee4a2a336a3c7c485baa1c06d78a931";
$City_ID=3176203;
$Units="metric";

function CallAPI($method, $url, $data = false)
{
    $curl = curl_init();

    switch ($method)
    {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);

            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_PUT, 1);
            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }

    // Optional Authentication:
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($curl, CURLOPT_USERPWD, "username:password");

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);

    curl_close($curl);

    return $result;
}

function Unix_Time_Converter($Unix_Time)
{
    $dt = new DateTime("@$Unix_Time");
    $dt->setTimeZone(new DateTimeZone('Europe/Rome'));
    return $dt->format('g:i a');
}

$Response=json_decode(CallAPI("GET","https://api.openweathermap.org/data/2.5/weather?id=".$City_ID."&appid=".$API_Key."&units=".$Units),true);

?>

<html>
    <head>
	
        <meta charset="UTF-8">
        <meta name="description" content="TEST! ;-)">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

    </head>

    <body onload="Auto_Dark_Mode()">

        <img id="Icon" src="Icons/<?php echo ($Response['weather'][0]['icon']) ?>.svg">
        <p><?php echo ($Response['weather'][0]['main']) ?></p>
        <table id="Table1">
            <tr>
                <td colspan="2" style="font-weight: bold">Temperature</td>
            </tr>
            <tr>
                <td>Temp: <?php echo ($Response['main']['temp']) ?>°C</td>
                <td>Feels Like: <?php echo ($Response['main']['feels_like']) ?>°C</td>
            </tr>
            <tr>
                <td>Max: <?php echo ($Response['main']['temp_max']) ?>°C</td>
                <td>Min: <?php echo ($Response['main']['temp_min']) ?>°C</td>
            </tr>
        </table>
        <table id="Table2" style="margin-top: 15px">
            <tr>
                <td><b>Pressure:</b> <?php echo ($Response['main']['pressure']) ?>hPa</td>
                <td><b>Humidity:</b> <?php echo ($Response['main']['humidity']) ?>%</td>
            </tr>
        </table>
        <table id="Table3" style="margin-top: 15px;">
            <tr>
                <td colspan="2" style="font-weight: bold">Wind</td>
            </tr>
            <tr>
                <td>Speed: <?php echo ($Response['wind']['speed']) ?>km/h</td>
                <td>Deg: <?php echo ($Response['wind']['deg']) ?>°</td>
            </tr>
        </table>
        <table id="Table4" style="margin-top: 15px; margin-bottom: 25px;">
            <tr>
                <td colspan="2" style="font-weight: bold">Time</td>
            </tr>
            <tr>
                <td>Sunrise: <?php echo (Unix_Time_Converter($Response['sys']['sunrise'])) ?></td>
                <td>Sunset: <?php echo (Unix_Time_Converter($Response['sys']['sunset'])) ?></td>
            </tr>
        </table>

        <?php echo ($Response['name'])." ".($Response['sys']['country'])." - ".($Response['id']) ?><br>

        <button id="Dark_Theme_Switch" onclick="Dark_Mode()">Dark-Mode</button>

    </body>

</html>

<script>

    // Dark_Mode //
	
	function Auto_Dark_Mode()
	{
		var Time = new Date().getHours();
		if (Time >= 19 || Time < 7) {
            Dark_Mode();
        }
	}

    function Dark_Mode()
	{
		document.body.classList.toggle("dark-theme");
		if (document.body.className == 'dark-theme') {
			document.getElementById("Icon").style.filter = "invert(100%) sepia(2%) saturate(161%) hue-rotate(202deg) brightness(112%) contrast(87%)";

            //document.getElementById("Table1").style = "border: 2px solid #808080";
            //document.getElementById("Table2").style = "border: 2px solid #808080; margin-top: 15px;";
            //document.getElementById("Table3").style = "border: 2px solid #808080; margin-top: 15px;";
            //document.getElementById("Table4").style = "border: 2px solid #808080; margin-top: 15px; margin-bottom: 25px;"
		}
		else {
			document.getElementById("Icon").style.filter = "none";

            //document.getElementById("Table1").style = "border: 2px solid #808080";
            //document.getElementById("Table2").style = "border: 2px solid #808080; margin-top: 15px;";
            //document.getElementById("Table3").style = "border: 2px solid #808080; margin-top: 15px;";
            //document.getElementById("Table4").style = "border: 2px solid #808080; margin-top: 15px; margin-bottom: 25px;"
		}
	}

</script>

<style>

    @import url('https://fonts.googleapis.com/css2?family=Kanit:wght@200&display=swap');

    * {
	    box-sizing: border-box;
    }

    html {
        margin: 0;
        padding:0;
        height: 100%;
        width: 100%;
        font-family: 'Kanit', sans-serif;
    }

    body {
        margin: auto;
        padding: 0;
        height: 100%;
        text-align: center;
        place-content: center;
    }

    body.dark-theme {
		color: #eee;
    	background: #121212;
	}

    img {
        margin-top: 20px;
        height: 250px;
        width: auto;
    }

    p {
        margin: 0;
        font-size: 100px;
    }

    table {
        border: 2px solid #808080;
        border-collapse: collapse;
        align: center;
        margin-right: auto;
        margin-left: auto;
    }

    td {
        border: 1px solid #808080;
        text-align: center;
        padding: 8px;
    }

    button {
        margin-top: 10px;
        margin-bottom: 15px;
    }

</style>