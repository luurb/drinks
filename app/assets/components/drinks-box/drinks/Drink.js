import React from 'react';
import SocialWrapper from './SocialWrapper';
import Product from './Product';

const Drink = () => {
    return (
        <div className="drink">
            <div className="drink__top">
                <span
                    className="drink__name"
                    style={{ borderBottom: '3px solid var(--yellow' }}
                >
                    White Russian
                </span>
                <SocialWrapper />
            </div>
            <div className="drink__bottom">
                <img
                    className="drink__img"
                    src="../images/drinks/mojito.jpg"
                    alt="Zdjęcie drinka"
                />
                <div className="drink__desc">
                    Wielu webmasterów i designerów używa Lorem Ipsum w budowie.
                    Wiele wersji tekstu ewoluowało i zmieniało się przez lata,
                    czasem przez przypadek, czasem specjalnie (humorystyczne
                    wstawki itd).
                    <div className="drink__products-box">
                        <span className="drink__products-header">
                            Składniki:
                        </span>
                        <Product name="wódka"/> 
                        <Product name="kawa"/> 
                        <Product name="syrop cukrowy"/> 
                        <Product name="lód"/> 
                    </div>
                </div>
            </div>
        </div>
    );
};

export default Drink;
