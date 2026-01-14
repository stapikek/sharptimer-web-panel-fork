<?php
global $social_links;
global $pagetitle;
?>
<footer>
    <div class="wrapper">
        <div>
            <h3>
                <?php echo $pagetitle ?? 'DECIDE Surf' ?>
            </h3>
            <p>
                <?php echo t('footer_desc'); ?>
            </p>
            <p>The website fork was created by <a href="https://github.com/stapikek" target="_blank" rel="noopener noreferrer" class="stapi-link">stapi</a></p>
        </div>
        <ul class="social-links">
            <?php if (!empty($social_links) && is_array($social_links)):
                foreach ($social_links as $link):
                    $name = htmlspecialchars($link['name']);
                    $url = htmlspecialchars($link['url']);
                    $icon = htmlspecialchars($link['icon']);
            ?>
                <li><a href="<?php echo $url; ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo $name; ?>">
                    <i class="<?php echo $icon; ?>"></i>
                </a></li>
            <?php endforeach; endif; ?>
        </ul>
    </div>
</footer>
