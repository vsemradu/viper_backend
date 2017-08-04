<div class="container">
    <div class="center-block">

        <div id="vk_auth"></div>
        <script type="text/javascript" src="//vk.com/js/api/openapi.js?120"></script>
        <script type="text/javascript">
            VK.init({apiId: <?= Yii::app()->params->vk_app_login_id ?>});
            VK.Widgets.Auth("vk_auth", {width: "400px", authUrl: '<?= Yii::app()->params->vk_redirect_uri_login ?>'});
        </script>

    </div>
</div>