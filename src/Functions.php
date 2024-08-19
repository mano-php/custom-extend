<?php
/**
 * 根据指定选项生成一个动态的三元表达式字符串。
 *
 * @param string $name 变量名称，将用于表达式中作为条件变量。
 * @param array $options 选项数组，其中键 (`key`) 代表需要与变量匹配的值，值 (`value`) 代表匹配成功时返回的颜色或状态值。
 *                       颜色格式可以是 'active', 'inactive', 'error', 'success', 'processing', 'warning' 或具体色值。
 *                       例如：
 *                       [
 *                           'enable' => 'success',
 *                           'disable' => 'error',
 *                       ]
 * @return string 返回构建好的三元表达式字符串，格式为：
 *                 ${name=="value1"?"color1":(name=="value2"?"color2":"default")}
 *                 其中，'default' 是当所有条件不匹配时的默认值。
 */
function setOptionsColor(string $name, array $options = []): string
{
    $expression = '${';
    $index = 0;

    foreach ($options as $value => $color) {
        if ($index > 0) {
            $expression .= ' : ';
        }
        if ($index > 0) {
            $expression .= '(';
        }
        $expression .= $name . ' == \'' . $value . '\' ? \'' . $color . '\'';
        $index++;
    }

    // 添加默认值
    $expression .= ' : "default"';

    // 关闭多余的括号
    $expression .= str_repeat(')', count($options) - 1);

    // 结束表达式
    $expression .= '}';

    return $expression;
}