<!-- HTML for static distribution bundle build -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ config('openapi.api.title') }}</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700|Source+Code+Pro:300,600|Titillium+Web:400,600,700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/swagger-ui/3.38.0/swagger-ui.css" integrity="sha512-tKlTmmIcJ/LXt94SNEbo3ZXMHhaa9quOeqk+sfMKYvTadSD2xSzmN95EOeITw7rmAQOuHStvbqA1W7fCU6RQZQ==" crossorigin="anonymous" />
    <style>
        html
        {
            box-sizing: border-box;
            overflow: -moz-scrollbars-vertical;
            overflow-y: scroll;
        }
        *,
        *:before,
        *:after
        {
            box-sizing: inherit;
        }
        body
        {
            margin:0;
            background: #fafafa;
        }
    </style>
</head>

<body>
<div id="swagger-ui"></div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/swagger-ui/3.38.0/swagger-ui-bundle.js" integrity="sha512-A1jSvGBkNnmx0I/cKKLnvH8V1zGyFYs+c9u3T8nffnFHr0vrZyNnlE6ccVjBavHw2gy5l7SV/2rvKfEn6YJYfg==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/swagger-ui/3.38.0/swagger-ui-standalone-preset.min.js" integrity="sha512-3WEWc88CIX/zjSP8KmM3J2Xxehu8ryaHb6xBxOnEL5ATDMYx6Hw41PSVl9PFJ0SRterPvBoIUgJeiLmn9ZZQrg==" crossorigin="anonymous"></script>

<script type="text/javascript">
    window.onload = function() {
        const ui = SwaggerUIBundle({
            url: "{!! $urlToDocs !!}",
            dom_id: '#swagger-ui',
            deepLinking: true,
            presets: [
                SwaggerUIBundle.presets.apis,
                SwaggerUIStandalonePreset
            ],
            plugins: [
                SwaggerUIBundle.plugins.DownloadUrl
            ],
            layout: "StandaloneLayout"
        });
        window.ui = ui;
    }
</script>
</body>
</html>
