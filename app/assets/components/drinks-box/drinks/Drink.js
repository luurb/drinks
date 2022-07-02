import React, { useCallback } from 'react';
import SocialWrapper from './SocialWrapper';

const Drink = React.forwardRef((props, ref) => {
   return (
      <div className="drink" ref={ref}>
         <div className="drink__top">
            <div className="drink__name-box">
               <div className="drink__categories-box">
                  {props.drink.categories.map((category, index) => (
                     <div
                        key={index}
                        className="drink__category-disc"
                        style={{ background: `var(--${category.color})` }}
                     ></div>
                  ))}
               </div>
               <span className="drink__name">{props.drink.name}</span>
            </div>
            <SocialWrapper />
         </div>
         <div className="drink__bottom">
            <img
               className="drink__img"
               src={props.drink.image}
               alt="Zdjęcie drinka"
               loading="lazy"
            />
            <div className="drink__desc">
               <div className="drink__desc-text">{props.drink.description}</div>
               <div className="drink__products-wrapper">
                  <span className="drink__products-header">Składniki:</span>
                  <div className="drink__products-box">
                     {props.drink.products.map((product) => (
                        <span
                           key={product.id}
                           className={product.active ? "drink__active-product" : ""}
                        >
                           {product.name}
                        </span>
                     ))}
                  </div>
               </div>
            </div>
         </div>
      </div>
   );
});

export default Drink;
