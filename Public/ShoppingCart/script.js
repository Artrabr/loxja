class CartItem {
  #value;
  #amount;

  constructor(container) {
    this.#amount = container.querySelector('.amount_of_products');
    this.#value = parseInt(this.#amount.value) || 0;
  }

  get value() {
    this.#value = parseInt(this.#amount.value) || 0;
    return this.#value;
  }

  set value(v) {
    this.#value = parseInt(v) || 0;
    this.#amount.value = this.#value;
  }
}

// Usage
document.querySelectorAll('.shopping_cart > div').forEach(div => {
  const item = new CartItem(div);
  const increase = div.querySelector('.increase')
  const decrease = div.querySelector('.decrease')

  const remove = div.querySelector('.remove')


  increase.addEventListener('click', () => {
    item.value = item.value + 1
  });

  decrease.addEventListener('click', () => {
    item.value = item.value - 1
  });


  remove.addEventListener('click', () => {
    item.value = item.value + 10
  });

});
