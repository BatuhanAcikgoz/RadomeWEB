{include file='header.tpl'}

<body id="page-top">

<!-- Wrapper -->
<div id="wrapper">

    <!-- Sidebar -->
    {include file='sidebar.tpl'}

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main content -->
        <div id="content">

            <!-- Topbar -->
            {include file='navbar.tpl'}

            <!-- Begin Page Content -->
            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">{$PRODUCTS}</h1>
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                        <li class="breadcrumb-item active">{$STORE}</li>
                        <li class="breadcrumb-item active">{$PRODUCTS}</li>
                    </ol>
                </div>

                <!-- Update Notification -->
                {include file='includes/update.tpl'}

                <div class="card shadow mb-4">
                    <div class="card-body">
                        <h5 style="display:inline">{$PRODUCT_TITLE}</h5>
                        <div class="float-md-right">
                            <a href="{$BACK_LINK}" class="btn btn-primary">{$BACK}</a>
                        </div>
                        <hr />

                        <ul class="nav nav-tabs">
                          <li class="nav-item">
                            <a class="nav-link" href="{$GENERAL_SETTINGS_LINK}">{$GENERAL_SETTINGS}</a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link" href="{$ACTIONS_LINK}">{$ACTIONS}</a>
                          </li>
                        </ul>

                        </br>

                        <!-- Success and Error Alerts -->
                        {include file='includes/alerts.tpl'}
                        
                    </div>
                </div>

                <!-- Spacing -->
                <div style="height:1rem;"></div>

                <!-- End Page Content -->
            </div>

            <!-- End Main Content -->
        </div>

        {include file='footer.tpl'}

        <!-- End Content Wrapper -->
    </div>

    <!-- End Wrapper -->
</div>

{include file='scripts.tpl'}

<script type="text/javascript">
    $(document).ready(() => {
        $('#inputRequiredProducts').select2({ placeholder: "No products selected" });
    })

    $(document).ready(() => {
        $('#inputRequiredGroups').select2({ placeholder: "No groups selected" });
    })

    $(document).ready(() => {
        $('#inputRequiredIntegrations').select2({ placeholder: "No integrations selected" });
    })
</script>

</body>
</html>