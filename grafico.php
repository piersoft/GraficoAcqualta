<?php 


$json_url = "http://api.acqualta.org/api/data/";
$json = file_get_contents($json_url);
$json=str_replace('},

]',"}

]",$json);
$dataj = json_decode($json,true);

//echo "<pre>";
//echo $dataj;
//print_r($dataj);
//echo "</pre>";
//$data=array($dataj);



function makeCSV($table){
$csv="";
foreach($table as $r){
$csv .= implode(",", $r).",\n";
}
return $csv;
}
$csv_data=makeCSV($dataj[data]);

//print_r($csv_data);

 $filename = "file";
 $XMLFile = fopen("file.csv", "w") or die("can't open file");
  

//  fclose($XMLFile);

//  $fp = fopen('file.csv','w');
$csv_fields=array();

$csv_fields[] = 'id';
$csv_fields[] = 'id_device';
$csv_fields[] = 'date_sent';
$csv_fields[] = 'tweet';
$csv_fields[] = 'level';
$csv_fields[] = 'battery_voltage';
$csv_fields[] = 'panel_voltage';
$csv_fields[] = 'temperature';
$csv_fields[] = 'csq';
$csv_fields[] = 'ber';
$csv_fields[] = 'latitude';
$csv_fields[] = 'longitude';
$csv_fields[] = 'description';
$csv_fields[] = 'twitter_enabled';
$csv_fields[] = 'location';
$csv_fields[] = 'district';
$csv_fields[] = 'alias';
$csv_fields[] = 'nil';
fputcsv($XMLFile, $csv_fields);

while($row = mysql_fetch_assoc($details)) {

     fputcsv($XMLFile,$row); 
   } 

fwrite($XMLFile, $csv_data);
fclose($XMLFile);

//header("Location:http://www.apposta.biz/prove/grafico.html");


?>


        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Ultime rilevazioni Acqualta.org</title>
        <link rel="stylesheet" href="style.css" type="text/css">
        <script src="http://www.apposta.biz/prove/js/amcharts.js" type="text/javascript"></script>        


        <script type="text/javascript">

        

        // declaring variables

        var chart;

        var dataProvider;

        

        // this method called after all page contents are loaded

        window.onload = function() {

            createChart();            

  loadCSV("http://www.apposta.biz/prove/file.csv");                                    

        }

        

        // method which loads external data

        function loadCSV(file) {

            if (window.XMLHttpRequest) {

                // IE7+, Firefox, Chrome, Opera, Safari

                var request = new XMLHttpRequest();

            }

            else {

                // code for IE6, IE5

                var request = new ActiveXObject('Microsoft.XMLHTTP');

            }

            // load

            request.open('GET', file, false);

            request.send();

            parseCSV(request.responseText);

        }

        

        // method which parses csv data

        function parseCSV(data){ 

            //replace UNIX new lines

            data = data.replace (/\r\n/g, "\n");

            //replace MAC new lines

            data = data.replace (/\r/g, "\n");

            //split into rows

            var rows = data.split("\n");

            // create array which will hold our data:

            dataProvider = [];

            

            // loop through all rows

            for (var i = 1; i < rows.length-2; i++){

                // this line helps to skip empty rows

                if (rows[i]) {                    

                    // our columns are separated by comma

                    var column = rows[i].split(",");  

                    

                    // column is array now 

                    // first item is date

                    var date = column[0];

                    // second item is value of the second column

                    var value1 = column[14];

                    // third item is value of the fird column 

                    var value4 = column[4];

                       var value2 = column[2];

                    // create object which contains all these items:

                    var dataObject = {date:date, value1:value1,value2:value2, value4:value4};

                    // add object to dataProvider array

                    dataProvider.push(dataObject);

                }

            }

            // set data provider to the chart

            chart.dataProvider = dataProvider;

            // this will force chart to rebuild using new data            

            chart.validateData();

        }

        

        // method which creates chart

        function createChart(){

            // chart variable is declared in the top

            chart = new AmCharts.AmSerialChart();

            // here we tell the chart name of category 

            // field in our data provider.

            // we called it "date" (look at parseCSV method)

            chart.categoryField = "value1";
            chart.depth3D = 30;
            chart.angle = 20;
             var categoryAxis = chart.categoryAxis;
                categoryAxis.labelRotation = 90;
                categoryAxis.gridPosition = "start";
                 categoryAxis.title = "Ultimi livelli registrati";

            // chart must have a graph

            var graph = new AmCharts.AmGraph();
            graph.valueField = "level";
                graph.colorField = "color";
                graph.balloonText = "[[category]]: [[value2]]";
                graph.type = "column";
                graph.lineAlpha = 0;
                graph.fillAlphas = 1;
                
                
            // graph should know at what field from data

            // provider it should get values.

            // let's assign value1 field for this graph

            graph.valueField = "value4";

            // and add graph to the chart

            chart.addGraph(graph);            

            // 'chartdiv' is id of a container 

            // where our chart will be                        

            chart.write('chartdiv');

        }

        </script>

              
<div class="main homepage" align="center">
        <div id="chartdiv" style="width:1100px; height:400px; background-color:#FFFFFF"></div>
