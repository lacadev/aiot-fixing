<?php
use Carbon_Fields\Block;
use Carbon_Fields\Field;

Block::make(__('Block Contact', 'gaumap'))
->add_fields([
    Field::make('separator', 'contact_spt', __('BLOCK CONTACT', 'gaumap'))->set_width(70),
    Field::make('html', 'contact_guide') ->set_width(30)
        ->set_html('<button class="contact-guide button">Hướng dẫn nhập</button>'),
    //Title
    Field::make('text', 'contact_title', __('', 'gaumap'))
        ->set_attribute('placeholder', 'Enter block title')
        ->set_attribute('data-step', '1')
        ->set_attribute('data-intro', 'Nhập tiêu đề của block'),
    //Description
    Field::make('textarea', 'contact_desc', __('', 'gaumap'))
        ->set_attribute('placeholder', 'Enter block description')
        ->set_attribute('data-step', '2')
        ->set_attribute('data-intro', 'Nhập mô tả của block'),
    //Google map
    Field::make('textarea', 'google_map', __('', 'gaumap'))
    ->set_attribute('placeholder', 'Iframe google map')
    ->set_attribute('data-step', '3')
    ->set_attribute('data-intro', 'Nhập iframe google map với width = 100%'),
    //Other contact methods
    Field::make('text', 'contact_method_spt', __('', 'gaumap')) ->set_width(20)
    ->set_default_value('Other contact methods')
    ->set_attribute('readOnly', 'true')
    ->set_attribute('data-step', '4')
    ->set_attribute('data-intro', 'Nhập thông tin liên hệ khác'),
    Field::make('complex', 'contact_method', __('', 'gaumap')) ->set_width(80)
    ->set_layout('tabbed-horizontal')
    ->add_fields([
        Field::make( 'text', 'name', __('','gaumap'))
        ->set_attribute('placeholder', 'Enter contact name'),
        Field::make( 'text', 'url', __('','gaumap'))
        ->set_attribute('placeholder', 'Enter contact url'),
    ])->set_header_template('<% if (name) { %><%- name %><% } %>'),
])
->set_render_callback(function ($fields, $attributes, $inner_blocks) {
    $title = !empty($fields['contact_title']) ? esc_html($fields['contact_title']) : '';
    $desc = !empty($fields['contact_desc']) ? wp_kses_post($fields['contact_desc']) : '';
    $googMap = !empty($fields['google_map']) ? $fields['google_map'] : '';
    $otherContact = !empty($fields['contact_method']) ? $fields['contact_method'] : '';
?>
    <section class="block-contact">
        <div class="google-map">
            <?= $googMap; ?>
        </div>
        <div class="info-contact">
            <?php
            if ($title):
                echo '<h2 class="block-title">' . $title . '</h2>';
            endif;

            if ($desc):
                echo '<div class="block-desc">' .  apply_filters('the_content', $desc) . '</div>';
            endif;

            if ($otherContact):
                echo '<div class="other-contact">';
                    foreach ($otherContact as $contact):
                        $name = !empty($contact['name']) ? esc_html($contact['name']) : '';
                        $url = !empty($contact['url']) ? esc_url($contact['url']) : '';
                        if ($name && $url):
                            echo '<a href="' . $url . '" target="_blank" class="contact-method">' . $name . '</a>';
                        endif;
                    endforeach;
                echo '</div>';
            endif;
            ?>

            <form  id="contactForm" action="#">
                <div class="form-row">
                    <div class="input-data">
                        <input type="text" name="first_name" required>
                        <div class="underline"></div>
                        <label for="first_name">First Name</label>
                    </div>
                    <div class="input-data">
                        <input type="text" name="last_name" required>
                        <div class="underline"></div>
                        <label for="last_name">Last Name</label>
                    </div>
                </div>
                <div class="form-row">
                    <div class="input-data">
                        <input type="text" name="email" required>
                        <div class="underline"></div>
                        <label for="email">Email</label>
                    </div>
                    <div class="input-data">
                        <input type="number" name="phone_number" required>
                        <div class="underline"></div>
                        <label for="phone_number">Phone</label>
                    </div>
                </div>
                <div class="form-row">
                    <div class="input-data textarea">
                        <textarea rows="8" cols="80" name="message" required></textarea>
                        <br />
                        <div class="underline"></div>
                        <label for="message">Write your message</label>
                        <br />
                    </div>
                </div>
				<div class="policy-scroll">
					<h2>個人情報の取扱いについて</h2>
					<br>
					<h3>この入力フォームで取得する個人情報の取り扱いは下記3項の利用目的のためであり、この目的の範囲を超えて利用することはございません。</h3>
					<ul>
					  <li><b>１．組織の名称</b><br>
						組織の名称：AIoT株式会社
					  </li>
					  <li><b>2．個人情報を関する管理者の氏名、所属及び連絡先</b><br>
						管理者名：個人情報保護管理者　タン バ ニャト（info@aiot-global.com)
						<br>
						連絡先：03-4500-6968
					  </li>
					  <li><b>3．個人情報の第三者提供</b><br>
						当社は、ご提供いただいた個人情報を次の場合を除き第三者に提供いたしません。
						  <br>
						  ・ご本人の同意がある場合
						  <br>
						  ・法令に基づく場合
						  <br>
						  ・人の生命、身体又は財産の保護のために必要がある場合であって、人の同意を得ることが困難であるとき
						  <br>
						  ・公衆衛生の向上又は児童の健全な育成の推進のために特に必要がある場合であって本人の、同意を得ることが困難であるとき
						  <br>
						  ・国の機関若しくは地方公共団体又はその委託を受けた者が法令の定める事務を遂行することに対して協力する必要がある場合であって、本人の同意を得ることによって当該事務の遂行に支障を及ぼすおそれがあるとき
					  </li>
					  <li><b>4．個人情報取扱いの委託</b><br>
						当社は、事業運営上業務の一部を外部に委託しており、業務委託先に対しては、個人情報を預けることがあります。この場合、個人情報を適切に取り扱っていると認められる委託先を選定し、契約等において個人情報の適正管理・機密保持などによりお客様の個人情報の漏洩防止に必要な事項を取決め、適切な管理を実施させます。
					  </li>
					  <li><b>5．個人情報の開示等の請求</b><br>
						お客様が当社に対してご自身の個人情報の開示等（利用目的の通知、開示、内容の訂正・追加・削除、利用の停止または消去、第三者への提供の停止、第三者提供記録の開示）に関して、当社「個人情報に関するお問合わせ窓口」に申し出ることができます。その際、当社はご本人を確認させていただいたうえで、合理的な期間内に対応いたします。開示等の申し出の詳細につきましては、下記の「個人情報に関するお問い合わせ窓口」までお問い合わせください。
						  <br>
						  <br>
						  <br>
						  〒104-0031 東京都中央区京橋１丁目１−５ セントラルビル 2階
　　　　　　　　		  <br>
						  AIoT株式会社　個人情報に関するお問い合わせ窓口
　　　　　　　　		  <br>
						  メールアドレス：info@aiot-global.com
　　　　　　　　		  <br>
						  TEL：03-4500-6968
						  <br>
						  <br>
					  </li>
					  <li><b>6．個人情報を提供されることの任意性について</b><br>
						お客様が当社に個人情報を提供されるかどうかは、お客様の任意によるものです。ただし、必要な項目をいただけない場合、各サービス等が適切な状態で提供できない場合があります。
					  </li>
					  <li><b>7．本Webサイトへアクセスしたことを契機として機械的に取得される情報</b><br>
						当社は、閲覧されたWebサイトのセキュリティ確保・ユーザーサービス向上のため、Cookieにより閲覧された方の情報を取得することがあります。
					  </li>
					</ul>
					<div style="text-align: right; padding: 2rem 1rem;">
						以上
					</div>
				  </div>
				<div class="form-row">
                    <div style="margin-left: 1.5rem;">
						<span>
							<input style="height: 2rem; width: 2rem;" type="checkbox" id="agree" name="agree" required>
							<span for="agree">「個人情報の取り扱い」に同意する</span>
						</span>
                        <div class="underline"></div>
                        <br />
                    </div>
                </div>
                <div class="form-row submit-btn">
                    <div class="input-data">
                        <div class="inner">
                            <button type="submit">
                                <svg viewBox="0 0 28 19" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M5.89153 9.60178C5.80696 9.60178 5.72285 9.57795 5.65139 9.5316L0.264398 6.04566C0.123825 5.95471 0.0561156 5.79378 0.0928988 5.63766C0.129448 5.48199 0.263461 5.36216 0.43168 5.33462L27.4215 0.910455C27.6351 0.875253 27.842 0.997259 27.9006 1.19186C27.9592 1.38624 27.85 1.59089 27.6485 1.66458L6.0457 9.57467C5.99603 9.59282 5.94355 9.60178 5.89177 9.60178H5.89153ZM1.58766 5.94443L5.94706 8.76523L23.4285 2.36448L1.58766 5.94443Z"></path>
                                    <path d="M15.9787 16.8496C15.8938 16.8496 15.8096 16.8269 15.739 16.7806L7.78951 11.5661C7.66748 11.4858 7.59802 11.3514 7.6061 11.2109C7.61443 11.0704 7.69959 10.9443 7.83019 10.8773L27.2847 0.952809C27.4617 0.863338 27.6786 0.900655 27.8104 1.04228C27.9419 1.18435 27.9519 1.39364 27.8347 1.54651C23.373 7.36008 16.6355 16.1768 16.361 16.6383C16.302 16.7376 16.199 16.8127 16.0815 16.8381C16.0477 16.8455 16.0132 16.8491 15.979 16.8491L15.9787 16.8496ZM8.85045 11.2768L15.8688 15.8807C17.2362 14.0016 23.3989 5.95283 26.0399 2.50776L8.85045 11.2768Z"></path>
                                    <path d="M8.62085 18.2992C8.49498 18.2992 8.39703 18.2321 8.2354 17.8804H8.22854C8.22854 17.8767 8.2283 17.8713 8.2283 17.8642C7.98757 17.3339 7.60506 16.1797 6.78591 13.7085C6.07328 11.5576 5.36801 9.40557 5.36801 9.40557C5.29674 9.18946 5.42825 8.96061 5.66138 8.89486C5.89353 8.82912 6.14063 8.9506 6.21115 9.16694C6.77783 10.8952 7.50539 13.1025 8.08504 14.842C8.02602 13.6248 7.95794 12.2473 7.90186 11.1185C7.8906 10.8924 8.07867 10.7011 8.3216 10.6906C8.566 10.6813 8.77072 10.8549 8.78223 11.0806C8.81578 11.7562 9.10989 17.6868 9.10989 17.8804C9.10989 18.0739 8.94925 18.2587 8.73301 18.2885C8.69187 18.2942 8.65514 18.2992 8.62061 18.2992H8.62085Z"></path>
                                    <path d="M8.75414 18.3008C8.65363 18.3008 8.55312 18.2587 8.47597 18.1744C8.31969 18.0043 8.3177 17.7257 8.47134 17.5525L12.1914 13.3571C12.3448 13.1836 12.5961 13.1819 12.7524 13.352C12.9087 13.5221 12.9107 13.8007 12.757 13.974L9.03694 18.1693C8.95935 18.257 8.85664 18.3008 8.75414 18.3008Z"></path>
                                </svg>
                                <?= _e('Send','gaumap');?>
                            </button>
                            <input type="hidden" name="_token" value="<?php echo wp_create_nonce('send_contact_form'); ?>">
                        </div>
                    </div>
                </div>
            </form>
            <div id="formMessage"></div>

            <div class="info-company">
                    <div class="info-item phone">
                        <a href="tel:<?= str_replace(['.', ',','-', ' '], '', getOption('phone_number'));?>">
                            <div class="icon-phone"></div>
                            <?= esc_html(getOption('phone_number'));?>
                        </a>
                    </div>
                    <div class="info-item email">
                        <a href="mailto:<?= esc_html(getOption('email'));?>">
                            <div class="icon-mail"></div>
                            <?= esc_html(getOption('email'));?>
                        </a>
                    </div>
                    <div class="info-item hour">
                        <div class="icon-hour"></div>
                        <?= esc_html(getOption('hour_working'));?>
                    </div>
                    <div class="info-item address">
                        <div class="icon-address"></div>
                        <?= esc_html(getOption('address'));?>
                    </div>
            </div>
        </div>
    </section>
<?php
});
