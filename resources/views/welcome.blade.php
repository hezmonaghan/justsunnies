<html>
	<head>
		<title>Just Sunnies, Laravel Auto-Complete Search</title>
		<link href='//fonts.googleapis.com/css?family=Lato:100' rel='stylesheet' type='text/css'>
		<style>
			@import url('https://fonts.googleapis.com/css?family=Lato:300,400,700');
			body {
				margin: 0;
				padding: 0;
				width: 100%;
				height: 100%;
				color: #B0BEC5;
				display: table;
				font-weight: 400;
				font-family: 'Lato';
				background: url(../img/hezmonaghan-background.jpg) center center;
			    position: relative;
			    background-size: cover;
			    z-index: 1;
			    font-family: 'Lato', sans-serif;
			}
			.container {
				text-align: center;
				display: table-cell;
				vertical-align: middle;
			}

			.content {
				text-align: center;
				display: inline-block;
				width: 50%;
			}

			.title {
				font-size: 96px;
				margin-bottom: 40px;
			}

			.quote {
				font-size: 24px;
			}
			.logo{
				width: 80px;
			    position: absolute;
			    bottom: 30px;
			    right: 30px;
			}
			.logo img{
				width: 100%;
			}
			p{
				color: #fff;
				font-size:22px;
				margin-bottom: 0px;
				font-weight: 300;
			}
			input{
				height: 40px;
				color: #333;
				font-size: 18px !important;
			}
		</style>
		<link href="{{ url('vendor/dist/css/selectize.bootstrap3.css') }}" rel="stylesheet">
	</head>
	<body>
		<div class="container">
			<div class="logo"><img src="http://hez.ninja/img/logo.svg" /></div>
			<div class="content">
				<select id="searchbox" name="q" placeholder="Search..." class="form-control"></select>
				<p>Search for a Car. Try a Brand, or even a Model.</p>
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
			                return '<div>' +escape(item.name)+'</div>';
			            }
			        },
			        optgroups: [
			            {value: 'product', label: 'Cars'}
			        ],
			        optgroupField: 'class',
			        optgroupOrder: ['product'],
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
