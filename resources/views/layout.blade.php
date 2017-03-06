<html>
	<head>
		<title>Laravel</title>
		
		<link href='//fonts.googleapis.com/css?family=Lato:100' rel='stylesheet' type='text/css'>

		<style>
			body {
				margin: 0;
				padding: 0;
				width: 100%;
				height: 100%;
				color: #B0BEC5;
				display: table;
				font-weight: 100;
				font-family: 'Lato';
			}

			.container {
				text-align: center;
				display: table-cell;
				vertical-align: middle;
			}

			.content {
				text-align: center;
				display: inline-block;
			}

			.title {
				font-size: 96px;
				margin-bottom: 40px;
			}

			.quote {
				font-size: 24px;
			}
		</style>
			<link href="{{ url('vendor/dist/css/selectize.bootstrap3.css') }}" rel="stylesheet">

		  

	</head>
	<body>
		<div class="container">
			<div class="content">
				<div style="width: 300px;" class="logo"><img src="img/logo.svg" alt="Hez Monaghan, Full-Stack Web Developer and Digital Designer"></div>
				<select id="searchbox" name="q" placeholder="Search products or categories..." class="form-control"></select>
			</div>
		</div>
		<script type="text/javascript">
        var root = '{{url("/")}}';
    </script>
    <script type="text/javascript" src='//code.jquery.com/jquery-1.10.2.min.js'></script>
		  <script type="text/javascript" src='{{ url("vendor/dist/js/standalone/selectize.min.js") }}'></script>
		<script>
			$(document).ready(function(){
			    $('#searchbox').selectize({
			        valueField: 'url',
			        labelField: 'name',
			        searchField: ['name'],
			        maxOptions: 10,
			        options: [],
			        create: false,
			        render: {
			            option: function(item, escape) {
			                return '<div><img src="'+ item.icon +'">' +escape(item.name)+'</div>';
			            }
			        },
			        optgroups: [
			            {value: 'product', label: 'Products'},
			            {value: 'category', label: 'Categories'}
			        ],
			        optgroupField: 'class',
			        optgroupOrder: ['product','category'],
			        load: function(query, callback) {
			            if (!query.length) return callback();
			            $.ajax({
			                url: root+'/api/search',
			                type: 'GET',
			                dataType: 'json',
			                data: {
			                    q: query
			                },
			                error: function() {
			                    callback();
			                },
			                success: function(res) {
			                    callback(res.data);
			                }
			            });
			        },
			        onChange: function(){
			            window.location = this.items[0];
			        }
			    });
			});
		</script>
	</body>
</html>
