<?php
namespace {
class MessageStorage
{
    private static $file = __DIR__ . '/../data/messages.json';

    public static function read()
    {
        if (!file_exists(self::$file)) return [];
        $json = file_get_contents(self::$file);
        $data = json_decode($json, true);
        return is_array($data) ? $data : [];
    }

    public static function save(array $msg)
    {
        $messages = self::read();
        $entry = $msg;
        $entry['timestamp'] = time();
        $messages[] = $entry;
        // Ensure directory exists
        $dir = dirname(self::$file);
        if (!is_dir($dir)) @mkdir($dir, 0777, true);
        file_put_contents(self::$file, json_encode($messages, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    /**
     * Overwrite all messages (used for delete/replace operations)
     * @param array $messages
     * @return void
     */
    public static function writeAll(array $messages)
    {
        $dir = dirname(self::$file);
        if (!is_dir($dir)) @mkdir($dir, 0777, true);
        file_put_contents(self::$file, json_encode($messages, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
}
