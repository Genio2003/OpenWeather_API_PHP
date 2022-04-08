<?php

/* Settings */
$API_Key="<API_KEY>";
$City_ID=3169070;
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

    <body>

        <img src="Icons/<?php echo ($Response['weather'][0]['icon']) ?>.svg">
        <p><?php echo ($Response['weather'][0]['main']) ?></p>
        <table>
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
        <table style="margin-top: 15px">
            <tr>
                <td><b>Pressure:</b> <?php echo ($Response['main']['pressure']) ?>hPa</td>
                <td><b>Humidity:</b> <?php echo ($Response['main']['humidity']) ?>%</td>
            </tr>
        </table>
        <table style="margin-top: 15px;">
            <tr>
                <td colspan="2" style="font-weight: bold">Wind</td>
            </tr>
            <tr>
                <td>Speed: <?php echo ($Response['wind']['speed']) ?>km/h</td>
                <td>Deg: <?php echo ($Response['wind']['deg']) ?>°</td>
            </tr>
        </table>
        <table style="margin-top: 15px; margin-bottom: 25px;">
            <tr>
                <td colspan="2" style="font-weight: bold">Time</td>
            </tr>
            <tr>
                <td>Sunrise: <?php echo (Unix_Time_Converter($Response['sys']['sunrise'])) ?></td>
                <td>Sunset: <?php echo (Unix_Time_Converter($Response['sys']['sunset'])) ?></td>
            </tr>
        </table>

        <?php echo ($Response['name'])." ".($Response['sys']['country'])." - ".($Response['id']) ?>

    </body>

</html>

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

    img {
        height: 250px;
        width: auto;
    }

    p {
        margin: 0;
        font-size: 100px;
    }

    table {
        border: 2px solid black;
        border-collapse: collapse;
        align: center;
        margin-right: auto;
        margin-left: auto;
    }

    td {
        border: 1px solid black;
        text-align: center;
        padding: 8px;
    }

</style>
