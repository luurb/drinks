import React from 'react';
import SocialWrapper from './SocialWrapper';

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
        </div>
    );
};

export default Drink;
