import React from 'react';
import SocialWrapper from './SocialWrapper';
import Product from './Product';

const Drink = ({ drink }) => {
   return (
      <div className="drink">
         <div className="drink__top">
            <div className="drink__name-box">
               <div className="drink__categories-box">
                  {drink.categories.map((category, index) => (
                     <div
                        key={index}
                        className="drink__category-disc"
                        style={{ background: `var(--${category.color})` }}
                     ></div>
                  ))}
               </div>
               <span className="drink__name">{drink.name}</span>
            </div>
            <SocialWrapper />
         </div>
         <div className="drink__bottom">
            <img
               className="drink__img"
               src={drink.image}
               alt="Zdjęcie drinka"
            />
            <div className="drink__desc">
               <div className="drink__desc-text">{drink.description}</div>
               <div className="drink__products-wrapper">
                  <span className="drink__products-header">Składniki:</span>
                  <div className="drink__products-box">
                     {drink.products.map((product) => (
                        <span key={product.id}>{product.name}</span>
                     ))}
                  </div>
               </div>
            </div>
         </div>
      </div>
   );
};

export default Drink;
