import React from 'react';

const FullDrink = ({ drink }) => {
   return (
      <div className="full-drink">
         {console.log(drink)}
         <div className="full-drink__wrapper">
            <img
               className="full-drink__img"
               src={drink.image}
               alt="Zdjęcie drinka"
               loading="lazy"
            />
            <div className="full-drink__text-box">
               <div className="full-drink__name">
                  {drink.categories.map((category, index) => (
                     <div
                        key={index}
                        className="full-drink__disc"
                        style={{ background: `var(--red)` }}
                     ></div>
                  ))}
                  {drink.name}
               </div>
               <div className="full-drink__products">
                  <span>Składniki:</span>
                  <div className="drink__products-box">
                     {drink.products.map((product) => (
                        <div
                           key={product.id}
                           className="drink__product full-drink__product"
                        >
                           {product.name}
                        </div>
                     ))}
                  </div>
               </div>
               <p className="full-drink__desc">{drink.description}</p>
            </div>
         </div>
      </div>
   );
};

export default FullDrink;
