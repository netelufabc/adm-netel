<script type="text/javascript">
    var timestamp = '<?= time(); ?>';
    function updateTime() {
        $('#timeonthefly').html(Date(timestamp));
        timestamp++;
    }
    $(function () {
        setInterval(updateTime, 1000);
    });
</script>

<?php
echo "<h3>INFO</h3>";

echo "<p id=\"timeonthefly\"></p>";

echo "Current TimeZone: " . date_default_timezone_get() . br();
echo "This page load TimeStamp: " . date('Y-m-d H:i:s') . br(2);

echo "Page Load Elapsed time: " . $this->benchmark->elapsed_time() . br();
echo "<strong>Memory usage: " . $this->benchmark->memory_usage() . br(2) . "</strong>";
echo "System Variables:" . br();
echo var_dump($config) . br();
echo "Session Info: " . br();
var_dump($this->session->userdata());

echo br();

echo "Base URL: " . $this->config->base_url() . br();
echo "Site URL: " . $this->config->site_url() . br(2);

echo "<div style = \"border: 1px solid; width:max-content\">";
echo "<strong>Database Info: </strong>" . br(2);
echo "Hostname: " . $this->db->hostname . br();
echo "Username: " . $this->db->username . br();
echo "Database: " . $this->db->database . br(2);
foreach ($this->db->list_tables() as $table) {
    echo "Tabela: <strong>" . $table . "</strong>" . br();
    foreach ($this->db->field_data($table) as $value) {
        echo $value->name . ": " . $value->type . ", " . $value->max_length . ", " . $value->primary_key . br();
    }
    echo br();
}
echo "</div>";

phpinfo();
