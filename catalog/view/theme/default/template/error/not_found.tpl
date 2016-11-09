<div class="col-main grid12-12 grid-col2-main in-col2">
    <div class="std container">
        <div class="col-md-12 text-center">
            
            <?php /*<div class="text-center">
                <h1 class="section-title-account">
                    <span class="background-account">
                        <span class="border-account">404 Not Found</span>
                    </span>
                </h1>
            </div>*/ ?>

            <div class="grid12-4">
                <p><img src="image/data/404.jpg" alt="404 Not Found" /></p>
            </div>

            <div class="grid12-8">
                <div class="page-title">
                    <h1><?php echo $text_error; ?></h1>
                </div>
                <dl>
                    <p>The page you requested was not found, and we have a fine guess why.</p>
                    <dd>
                        <ul class="list-unstyled">
                            <li>If you typed the URL directly, please make sure the spelling is correct.</li>
                            <li>If you clicked on a link to get here, the link is outdated.</li>
                        </ul>
                    </dd>
                </dl>
                <dl>
                    <h3>What can you do?</h3>
                    <dd>Have no fear, help is near! There are many ways you can get back on track.</dd>
                    <dd>
                        <ul class="list-unstyled">
                            <li><a href="#" onclick="history.go(-1); return false;">Go back</a> to the previous page.</li>
                            <li>Use the search bar at the top of the page to search for your products.</li>
                            <li>Follow these links to get you back on track!<br><a href="<?php echo $home; ?>">Store Home</a> <span class="separator">|</span> <a href="<?php echo $account; ?>">My Account</a></li>
                        </ul>
                    </dd>
                </dl>
            </div>
        </div>
    </div>
</div>