<?php
$accountSocials = $account->getMetaData('social_links', true);

$socials = (!empty($accountSocials) && $accountSocials !== '[]')
    ? Theme::convertSocialLinksToArray($accountSocials)
    : [];
?>

<?php if(!empty($socials)): ?>
    <ul class="agent-social">
        <?php $__currentLoopData = $socials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $social): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if((! $social->getIcon() && ! $social->getImage()) || ! $social->getUrl()) continue; ?>
            <li>
                <a href="<?php echo e($social->getUrl()); ?>" target="_blank" rel="noopener noreferrer">
                    <?php echo $social->getIconHtml(); ?>
                </a>
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
<?php endif; ?>
