<?php
use Carbon_Fields\Block;
use Carbon_Fields\Field;

Block::make(__('Block Timeline', 'mms'))
    ->add_fields([
        Field::make('separator', 'timeline_spt', __('BLOCK TIMELINE', 'mms'))->set_width(70),

        // Board of Directors / Team Members
        Field::make('complex', 'members', __('Team Members', 'mms'))
            ->set_layout('tabbed-horizontal')
            ->add_fields([
                Field::make('image', 'photo', __('Photo', 'mms')),
                Field::make('text', 'role', __('Position', 'mms'))
                    ->set_attribute('placeholder', 'Enter role in Japanese'),
                Field::make('text', 'name', __('Name', 'mms'))
                    ->set_attribute('placeholder', 'Enter name'),
                Field::make('text', 'name_kana', __('Name JP', 'mms'))
                    ->set_attribute('placeholder', 'Enter name kana'),

                // Nested History for each member
                Field::make('complex', 'member_history', __('Individual History', 'mms'))
                    ->set_layout('tabbed-horizontal')
                    ->add_fields([
                        Field::make('text', 'year', __('Year', 'mms'))
                            ->set_attribute('placeholder', 'e.g. 2006年06月'),
                        Field::make('rich_text', 'desc', __('Description', 'mms'))
                            ->set_attribute('placeholder', 'Enter short description')
                    ])->set_header_template('<% if (year) { %><%- year %><% } %>'),
            ])->set_header_template('<% if (name) { %><%- name %><% } %>'),
    ])
    ->set_render_callback(function ($fields, $attributes, $inner_blocks) {
        $members = !empty($fields['members']) ? $fields['members'] : [];
        ?>
    <section class="block-timeline">
        <div class="inner">

            <?php if (!empty($members)): ?>
                <div class="member-cards">
                    <div class="member-card">
                        <?php foreach ($members as $index => $member): ?>
                            <div class="member-detail-card"
                                onclick="document.getElementById('member-history-<?php echo $index; ?>').scrollIntoView({behavior: 'smooth'})"
                                style="cursor: pointer;">
                                <div class="member-header content">
                                    <div class="member-identity">
                                        <figure class="member-photo">
                                            <?php if ($member['photo']): ?>
                                                <img src="<?php echo esc_url(getImageUrlById($member['photo'], 400, 400)); ?>"
                                                    alt="<?php echo esc_attr($member['name']); ?>">
                                            <?php endif; ?>
                                        </figure>
                                        <div class="member-name-group">
                                            <div class="member-roles">
                                                <strong class="role-jp"><?php echo esc_html($member['role']); ?></strong>
                                            </div>
                                            <p class="member-name"><?php echo esc_html($member['name']); ?></p>
                                            <strong class="member-name-kana"><?php echo esc_html($member['name_kana']); ?></strong>
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
                            <div class="history-row" id="member-history-<?php echo $index; ?>">
                                <div class="member-roles">
                                    <span class="role-jp"><?php echo esc_html($member['role']); ?>
                                    </span>
                                </div>
                                <div class="member-identity">
                                    <div class="member-photo">
                                        <?php if ($member['photo']): ?>
                                            <img src="<?php echo esc_url(getImageUrlById($member['photo'], 400, 400)); ?>"
                                                alt="<?php echo esc_attr($member['name']); ?>">
                                        <?php endif; ?>
                                    </div>
                                    <div class="member-name-group">
                                        <h3 class="member-name">
                                            <?php echo esc_html($member['name']); ?>
                                        </h3>
                                        <p class="member-name-kana">
                                            <?php echo esc_html($member['name_kana']); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <?php foreach ($member_history as $h_item): ?>
                                <div class="history-row">
                                    <div class="history-year"><?php echo esc_html($h_item['year']); ?></div>
                                    <div class="history-content">
                                        <div class="desc-short"><?php echo $h_item['desc']; ?></div>
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
