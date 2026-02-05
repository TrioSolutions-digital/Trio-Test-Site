<?php
/*
Plugin Name: PageSpeed Monitor
Description: A custom plugin to monitor and display Google PageSpeed Insights scores.
Version: 1.0
Author: Your Name
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

function fetch_pagespeed_data($url, $strategy = 'mobile') {
    $api_key = 'AIzaSyAzojHTDQ-QXqrtOjJK14hu0jDNH9O8jQE';
    $api_url = "https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url=" . urlencode($url) . "&strategy={$strategy}&key=" . $api_key;

	$response = wp_remote_get($api_url);

	if (is_wp_error($response)) {
		return false;
	}

	$data = json_decode(wp_remote_retrieve_body($response), true);

	return $data;
}

function save_pagespeed_data() {
    $url_to_test = get_option('pagespeed_monitor_url', home_url());

	 $mobile_data = fetch_pagespeed_data($url_to_test, 'mobile');
    $desktop_data = fetch_pagespeed_data($url_to_test, 'desktop');

    if ($mobile_data) {
        $mobile_history = get_option('pagespeed_history_mobile', []);
        array_unshift($mobile_history, $mobile_data); // newest first
        $mobile_history = array_slice($mobile_history, 0, 7); // keep last 7
        update_option('pagespeed_history_mobile', $mobile_history);
        update_option('pagespeed_data_mobile', $mobile_data); // current snapshot
    }

    if ($desktop_data) {
        $desktop_history = get_option('pagespeed_history_desktop', []);
        array_unshift($desktop_history, $desktop_data);
        $desktop_history = array_slice($desktop_history, 0, 7);
        update_option('pagespeed_history_desktop', $desktop_history);
        update_option('pagespeed_data_desktop', $desktop_data);
    }
}

// Schedule the function daily
add_action('wp_pagespeed_daily_event', 'save_pagespeed_data');

// Schedule the event if not already scheduled
if (!wp_next_scheduled('wp_pagespeed_daily_event')) {
    wp_schedule_event(time(), 'daily', 'wp_pagespeed_daily_event');
}

function pagespeed_dashboard_widget() {
    $mobile = get_option('pagespeed_data_mobile');
    $desktop = get_option('pagespeed_data_desktop');

    function get_score_class($score) {
        if ($score >= 90) return 'green';
        if ($score >= 50) return 'orange';
        return 'red';
    }

function render_column($data, $label, $history_key) {
    if (!$data || !isset($data['lighthouseResult']['categories']['performance']['score'])) {
        echo '<div class="ps-col"><h4>' . esc_html($label) . '</h4><p>Data not available.</p></div>';
        return;
    }

    $score = $data['lighthouseResult']['categories']['performance']['score'] * 100;
    $fcp = $data['lighthouseResult']['audits']['first-contentful-paint']['displayValue'] ?? 'N/A';
    $lcp = $data['lighthouseResult']['audits']['largest-contentful-paint']['displayValue'] ?? 'N/A';
    $si  = $data['lighthouseResult']['audits']['speed-index']['displayValue'] ?? 'N/A';
    $cls = $data['lighthouseResult']['audits']['cumulative-layout-shift']['displayValue'] ?? 'N/A';
    $color = get_score_class($score);

    // Compare with yesterday’s score
    $history = get_option($history_key, []);
    $yesterday = $history[1]['lighthouseResult']['categories']['performance']['score'] * 100 ?? null;
    $trend_icon = '';
    if ($yesterday !== null) {
        if ($score > $yesterday) {
            $trend_icon = ' 🔼';
        } elseif ($score < $yesterday) {
            $trend_icon = ' 🔽';
        } else {
            $trend_icon = ' ➖';
        }
    }

    echo '<div class="ps-col">';
    echo '<h4>' . esc_html($label) . '</h4>';
    echo '<div class="pagespeed-score ' . esc_attr($color) . '">' . esc_html($score) . $trend_icon . '</div>';
    echo '<p><strong>FCP:</strong> ' . esc_html($fcp) . '</p>';
    echo '<p><strong>LCP:</strong> ' . esc_html($lcp) . '</p>';
    echo '<p><strong>Speed Index:</strong> ' . esc_html($si) . '</p>';
    echo '<p><strong>CLS:</strong> ' . esc_html($cls) . '</p>';
    echo '</div>';
}


    echo '<style>
        .ps-container {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            font-family: sans-serif;
            flex-wrap: wrap;
        }
        .ps-col {
            flex: 1 1 45%;
            background: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 0 6px rgba(0,0,0,0.1);
        }
        .ps-col h4 {
            margin-top: 0;
        }
        .pagespeed-score {
            font-size: 2em;
            font-weight: bold;
            color: white;
            text-align: center;
            border-radius: 6px;
            padding: 12px;
            margin-bottom: 10px;
        }
        .pagespeed-score.green { background-color: #0cce6b; }
        .pagespeed-score.orange { background-color: #ffa400; }
        .pagespeed-score.red { background-color: #ff4e42; }
    </style>';

    echo '<div class="ps-container">';
	render_column($mobile, 'Mobile', 'pagespeed_history_mobile');
	render_column($desktop, 'Desktop', 'pagespeed_history_desktop');
    echo '</div>';
}



function add_pagespeed_dashboard_widget() {
    wp_add_dashboard_widget(
        'pagespeed_dashboard_widget',
        'PageSpeed Insights',
        'pagespeed_dashboard_widget'
    );
}

add_action('wp_dashboard_setup', 'add_pagespeed_dashboard_widget');

add_action('admin_init', function() {
    register_setting('pagespeed_monitor_options', 'pagespeed_monitor_url');
});

add_action('admin_menu', function() {
    add_options_page(
        'PageSpeed Monitor Settings',
        'PageSpeed Monitor',
        'manage_options',
        'pagespeed-monitor',
        'pagespeed_monitor_settings_page'
    );
});

function pagespeed_monitor_settings_page() {
    ?>
    <div class="wrap">
        <h1>PageSpeed Monitor Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('pagespeed_monitor_options'); ?>
            <?php do_settings_sections('pagespeed_monitor_options'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">URL to Test</th>
                    <td>
                        <input type="text" name="pagespeed_monitor_url" value="<?php echo esc_attr(get_option('pagespeed_monitor_url', 'https://example.com')); ?>" style="width: 400px;" />
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}


// save_pagespeed_data();