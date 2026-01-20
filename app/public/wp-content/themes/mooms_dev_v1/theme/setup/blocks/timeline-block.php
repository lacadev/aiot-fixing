<?php
use Carbon_Fields\Block;
use Carbon_Fields\Field;

Block::make(__('Block Timeline', 'gaumap')) 
->add_fields([
    Field::make('separator', 'timeline_spt', __('BLOCK TIMELINE', 'gaumap'))->set_width(70),
    
    // Board of Directors / Team Members
    Field::make('complex', 'members', __('Team Members', 'gaumap'))
        ->set_layout('tabbed-horizontal')
        ->add_fields([
            Field::make('image', 'photo', __('Photo', 'gaumap')),
            Field::make('text', 'role', __('Role (JP)', 'gaumap'))
                ->set_attribute('placeholder', 'Enter role in Japanese'),
            Field::make('text', 'role_en', __('Role (EN)', 'gaumap'))
                ->set_attribute('placeholder', 'Enter role in English'),
            Field::make('text', 'name', __('Name', 'gaumap'))
                ->set_attribute('placeholder', 'Enter name'),
            Field::make('text', 'name_kana', __('Name Kana', 'gaumap'))
                ->set_attribute('placeholder', 'Enter name kana'),
            
            // Nested History for each member
            Field::make('complex', 'member_history', __('Individual History', 'gaumap'))
                ->set_layout('tabbed-horizontal')
                ->add_fields([
                    Field::make('text', 'year', __('Year', 'gaumap'))
                        ->set_attribute('placeholder', 'e.g. 2006年06月'),
                    Field::make('text', 'desc_short', __('Description (Short/Bold)', 'gaumap'))
                        ->set_attribute('placeholder', 'Enter short description'),
                    Field::make('textarea', 'desc_full', __('Description (Full)', 'gaumap'))
                        ->set_attribute('placeholder', 'Enter full details'),
                ])->set_header_template('<% if (year) { %><%- year %><% } %>'),
        ])->set_header_template('<% if (name) { %><%- name %><% } %>'),
])
->set_render_callback(function ($fields, $attributes, $inner_blocks) {
    $members = !empty($fields['members']) ? $fields['members'] : [];
?>
    <section class="block-timeline">
        <div class="inner">
            
            <?php if (!empty($members)): ?> 
                <div id="icbCards-1" class="wp-block-icb-cards">
                <div class="members-section icbCards columns-3 columns-tablet-2 columns-mobile-1 vertical gap-grid">
                    <?php foreach ($members as $index => $member): ?>
                            <div class="member-detail-card card card-0 default first4Theme" onclick="document.getElementById('member-history-<?= $index; ?>').scrollIntoView({behavior: 'smooth'})" style="cursor: pointer;">
                                <div class="member-header content">
                                    <div class="member-identity">
                                        <figure class="member-photo">
                                            <?php if ($member['photo']): ?>
                                                    <img src="<?= esc_url(getImageUrlById($member['photo'], 400, 400)); ?>"
                                                        alt="<?= esc_attr($member['name']); ?>">
                                            <?php endif; ?>
                                        </figure>
                                        <div class="member-name-group">
                                            <div class="member-roles">
                                                <strong class="role-jp"><?= esc_html($member['role']); ?></strong>
                                            </div>
                                            <p class="member-name"><?= esc_html($member['name']); ?></p>
                                            <strong class="member-name-kana"><?= esc_html($member['name_kana']); ?></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                </div>    
                </div>
                <div class="history-list">
                <?php foreach ($members as $index => $member):
                            $member_history = !empty($member['member_history']) ? $member['member_history'] : [];
                            ?>
                                <?php if (!empty($member_history)): ?>
                                            <div class="history-row" id="member-history-<?= $index; ?>">
                                                <div class="member-roles">
                                                <span class="role-jp"><?= esc_html($member['role']); ?>
                                                    </span>
                                                    <span class="role-en">(
                                                        <?= esc_html($member['role_en']); ?>)
                                                    </span>
                                                </div>
                                                <div class="member-identity">
                                                    <div class="member-photo">
                                                        <?php if ($member['photo']): ?>
                                                                <img src="<?= esc_url(getImageUrlById($member['photo'], 400, 400)); ?>"
                                                                    alt="<?= esc_attr($member['name']); ?>">
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="member-name-group">
                                                        <h3 class="member-name">
                                                            <?= esc_html($member['name']); ?>
                                                        </h3>
                                                        <p class="member-name-kana">
                                                            <?= esc_html($member['name_kana']); ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php foreach ($member_history as $h_item): ?>
                                                    <div class="history-row">
                                                        <div class="history-year"><?= esc_html($h_item['year']); ?></div>
                                                        <div class="history-content">
                                                            <div class="desc-short"><strong><?= esc_html($h_item['desc_short']); ?></strong></div>
                                                            <div class="desc-full"><?= wp_kses_post($h_item['desc_full']); ?></div>
                                                        </div>
                                                    </div>
                                            <?php endforeach; ?>
                                <?php endif; ?>
                <?php endforeach; ?>
            </div>    
            <?php endif; ?>
        </div>
    </section>
<?php
});
