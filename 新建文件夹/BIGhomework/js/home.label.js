const radioButtons = document.querySelectorAll('input[type="radio"]');
const labels = document.querySelectorAll('form label');

// 为每个radio按钮添加点击事件监听器
radioButtons.forEach((radioButton, index) => {
    radioButton.addEventListener('click', () => {
        // 取消其他所有按钮的选中状态
        radioButtons.forEach((btn) => {
            btn.checked = false;
        });

        // 取消其他所有label的选中样式
        labels.forEach((label) => {
            label.classList.remove('selected');
        });

        // 设置当前按钮为选中状态
        radioButton.checked = true;

        // 为当前label添加选中样式
        labels[index].classList.add('selected');
    });
});