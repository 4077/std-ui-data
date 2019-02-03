<div class="{__NODE_ID__}" instance="{__INSTANCE__}">

    <table hover="hover" level="{LEVEL}">
        <!-- node -->
        <tr class="index {LEVEL_CLASS} {EVEN_CLASS} sortable" node_path="{PATH}">
            <td class="left index" hover="hover">
                {INDEX}
            </td>
            <td class="right {CLASS} {EXPAND_CLASS}" type="{VALUE_TYPE}" hover="hover">
                <!-- node/expand_icon -->
                <div class="icon"></div>
                <!-- / -->
                <div class="value">{CONTENT}</div>
            </td>
        </tr>
        <!-- node/value -->
        <tr class="{node/LEVEL_CLASS}" node_path="{node/PATH}">
            <td class="left value" hover="hover">

            </td>
            <td class="right">
                {CONTENT}
            </td>
        </tr>
        <!-- / -->
        <!-- / -->
    </table>

    {ADD_BUTTON}

</div>
