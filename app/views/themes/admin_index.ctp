<div class="themes">
    <h2><?php echo $this->pageTitle; ?></h2>

    <div class="current-theme">
        <h3><?php __('Current Theme'); ?></h3>
        <div class="screenshot"><?php echo $html->image($currentTheme['Theme']['screenshot']); ?></div>
        <h3><?php echo $currentTheme['Theme']['name'] . ' ' . __('by', true) . ' ' . $currentTheme['Theme']['author'] ?></h3>
        <p class="description"><?php echo $currentTheme['Theme']['description']; ?></p>
        <p class="regions"><?php echo __('Regions supported: ', true) . implode(', ', Set::extract('/region', $currentTheme['Theme']['Regions'])); ?></p>
        <div class="clear"></div>
    </div>

    <div class="available-themes">
        <h3><?php __('Available Themes'); ?></h3>
        <ul>
        <?php
            foreach ($themesData AS $theme) {
                if ($theme['Theme']['alias'] != $currentTheme['Theme']['alias']) {
                    echo '<li>';
                        echo $html->tag('div', $html->image('/themed/' . $theme['Theme']['alias'] . '/img/' . $theme['Theme']['screenshot']), array('class' => 'screenshot'));
                        echo $html->tag('h3', $theme['Theme']['name'] . ' ' . __('by', true) . ' ' . $theme['Theme']['author'], array());
                        echo $html->tag('p', $theme['Theme']['description'], array('class' => 'description'));
                        echo $html->tag('p', __('Regions supported: ', true) . implode(', ', Set::extract('/region', $theme['Theme']['Regions'])), array('class' => 'regions'));
                        echo $html->tag('div', $html->link(__('Activate', true), array('action' => 'activate', $theme['Theme']['alias'])), array('class' => 'actions'));
                    echo '</li>';
                }
            }
        ?>
        </ul>
        <div class="clear"></div>
    </div>
</div>