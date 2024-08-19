<?php
/**
 * 根据指定选项生成一个动态的三元表达式字符串。
 *
 * @param string $name 变量名称，将用于表达式中作为条件变量。
 * @param array $options 选项数组，每个选项包含两个键：
 *                       - 'value': 需要与变量匹配的值
 *                       - 'color': 匹配成功时返回的颜色或状态值。颜色格式可以是 'active', 'inactive', 'error', 'success', 'processing', 'warning' 或具体色值。
 *                       例如：
 *                       [
 *                           ['value' => 'enable', 'color' => 'success'],
 *                           ['value' => 'disable', 'color' => 'error'],
 *                       ]
 * @return string 返回构建好的三元表达式字符串，格式为：
 *                 ${name=="value1"?"color1":(name=="value2"?"color2":"default")}
 *                 其中，'default' 是当所有条件不匹配时的默认值。
 */
function setOptionsColor(string $name, array $options = []): string
{
    $expression = '${';
    foreach ($options as $index => $option) {
        $value = $option['value'];
        $color = $option['color'];
        if ($index > 0) {
            $expression .= ' : ';
        }
        if ($index > 0) {
            $expression .= '(';
        }
        $expression .= $name . ' == \'' . $value . '\' ? \'' . $color . '\'';
    }
    $expression .= ' : "default"';
    $expression .= str_repeat(')', count($options) - 1);
    $expression .= '}';
    return $expression;
}