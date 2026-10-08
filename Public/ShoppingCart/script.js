function removeWrapper(something) {
  const cartItem = something.closest('.cart_item_wrapper');
  if (cartItem) {
    cartItem.remove();
  }
}

class CartItem {
  #value;
  #amount;
  #limit;
  #past_zero;
  #container;

  constructor(container) {
    this.#amount = container.querySelector('.amount_of_products');
    this.#value = parseInt(this.#amount.value) || 0;
    this.#limit = parseInt(container.querySelector('.increase').getAttribute('estoque')) || 0;
    this.#past_zero = false;
    this.#container = container;
  }

  get value() {
    this.#value = parseInt(this.#amount.value) || 0;
    return this.#value;
  }

  set value(v) {
    if (v < 0) {
      if (this.#past_zero) {
        removeWrapper(this.#container)
        return
      }
      this.#amount.value = this.#value
      this.#value = 0;
      setTimeout(() => {
        this.#past_zero = true;
      }, 500);
      return
    }
    this.#value = v ?? 0;
    if (this.#value > this.#limit) {
      this.#amount.value = this.#limit
      this.#value = this.#limit
    } else {
      this.#amount.value = this.#value;
    }
  }
}

// Usage
document.querySelectorAll('.shopping_cart .cart_item_actions').forEach(div => {
  const item = new CartItem(div);
  const increase = div.querySelector('.increase')
  const decrease = div.querySelector('.decrease')

  /** @type {HTMLInputElement | null} */
  const amount_of_products = div.querySelector('.amount_of_products')
  const limit = increase.getAttribute('estoque')

  const remove = div.querySelector('.remove')

  amount_of_products.addEventListener('input', (event) => {
    item.value = event.target.value
  });

  increase.addEventListener('click', () => {
    item.value = item.value + 1
  });

  decrease.addEventListener('click', () => {
    item.value = item.value - 1
  });


  remove.addEventListener('click', () => {
    removeWrapper(div)
  });

});
