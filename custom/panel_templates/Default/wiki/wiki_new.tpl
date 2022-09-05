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
                        <h1 class="h3 mb-0 text-gray-800">{$WIKI}</h1>
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active"><a href="{$WIKI_PANEL_LINK}">{$WIKI}</a></li>
                            <li class="breadcrumb-item active">{$NEW_PAGE}</li>
                        </ol>
                    </div>

                    <!-- Update Notification -->
                    {include file='includes/update.tpl'}
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <h5 style="display:inline">{$NEW_PAGE}</h5>
                            <div class="float-md-right"><a href="{$BACK_LINK}" class="btn btn-warning">{$BACK}</a></div>
                            <hr />
                            <!-- Success and Error Alerts -->
                            {include file='includes/alerts.tpl'}
                            <form action="" method="post">
                                <div class="form-group">
                                    <label for="InputPageTitle">{$WIKI_PAGE_TITLE}</label>
                                    <input type="text" id="InputPageTitle" placeholder="{$WIKI_PAGE_TITLE}"
                                        name="wiki_page_title" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="InputWikiID">{$WIKI_PAGE_ID}</label>
                                    <input type="text" id="InputWikiID" placeholder="{$WIKI_PAGE_ID}"
                                        name="wiki_page_id" class="form-control"
                                        value="{$WIKI_PAGE_ID_VALUE}">
                                </div>
                                {if !$SUB_PAGED}
                                    <div class="form-group">
                                        <label for="InputWikiParent">{$WIKI_PAGE_PARENT}</label>
                                        <select class="form-control" id="InputWikiParent" name="InputWikiParent">
                                            <option value=null>none</option>
                                            {foreach from=$WIKI_PAGES item=wiki_page}
                                                {if ($wiki_page->getParent() == "null" && $wiki_page->getNameID() != $WIKI_PAGE_ID_VALUE)}
                                                <option value={$wiki_page->getNameID()} {if $wiki_page->getNameID() eq $WIKI_PAGE_PARENT_VALUE} selected{/if}>{$wiki_page->getNameID()}
                                                </option>
                                                {/if}
                                            {/foreach}
                                        </select>
                                    </div>
                                {/if}
                                <!--<div class="form-group">
                                    <label for="InputWikiParent">{$WIKI_PAGE_PARENT}</label>
                                    <input type="text" id="InputWikiParent" placeholder="{$WIKI_PAGE_PARENT}"
                                        name="wiki_page_parent" class="form-control"
                                        value="null">
                                </div>-->


                                <div class="form-group">
                                    <label for="InputWikiButton">{$WIKI_PAGE_BUTTON}</label>
                                    <input type="text" id="InputWikiButton" placeholder="{$WIKI_PAGE_BUTTON}"
                                        name="wiki_page_button" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="InputWikiIcon">{$WIKI_PAGE_ICON}</label>
                                    <input type="text" id="InputWikiIcon" placeholder="{$ICON_EXAMPLE}"
                                        name="wiki_page_icon" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="InputWikiContext">{$WIKI_PAGE_CONTEXT}</label>
                                    <textarea name="wiki_page_context" rows="3" id="InputWikiContext"
                                        class="form-control"
                                        placeholder="{$WIKI_PAGE_CONTEXT}">{$WIKI_PAGE_CONTEXT_VALUE}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="InputWikiEnabled">{$WIKI_PAGE_ENABLED}</label>
                                    <select class="form-control" id="InputWikiEnabled" name="InputWikiEnabled">
                                        <option value="1" selected>Enable</option>
                                        <option value="0">Disable</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input type="hidden" name="token" value="{$TOKEN}">
                                    <input type="submit" class="btn btn-primary" value="{$SUBMIT}">
                                </div>
                            </form>
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

</body>

</html>