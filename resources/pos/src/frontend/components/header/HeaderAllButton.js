import { faHand, faList } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import React, {useState} from 'react';
import {Nav, Col} from 'react-bootstrap-v5';
import PosCalculator from './PosCalculator';

const HeaderAllButton = (props) => {
    const {setOpneCalculator, opneCalculator, goToDetailScreen,goToHoldScreen, holdListData} = props
    const [isFullscreen, setIsFullscreen] = useState(false);
    const fullScreen = () => {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen();
            setIsFullscreen(true);
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
                setIsFullscreen(false);
            }
        }
    };

    const opneCalculatorModel = () => {
        if(opneCalculator){
            setOpneCalculator(false)
        } else {
            setOpneCalculator(true)
        }
    }
    return (
        <>
         {/* <Col className='pos-header-btn'> */}
        <Nav className='align-items-center header-btn-grp justify-xxl-content-end justify-lg-content-center justify-content-start flex-nowrap pb-xxl-0 pb-lg-2 pb-2 '>
            <Nav.Item className='d-flex align-items-center position-relative justify-content-center ms-3 nav-pink'>
                <Nav.Link className='pe-0 ps-1 text-white' onClick={(e) => {
                    e.stopPropagation();
                    goToHoldScreen()
                }}>
                    <FontAwesomeIcon icon={faList} className='fa-2x'/>
                    {/* <i className="bi bi-hand fa-2x"/> */}
                </Nav.Link>
                <div className='hold-list-badge'>{holdListData.length ? holdListData.length : 0}</div>
            </Nav.Item>
            <Nav.Item className='d-flex align-items-center justify-content-center ms-3 nav-green'>
                <Nav.Link className='pe-0 ps-1 text-white' onClick={(e) => {
                            e.stopPropagation();
                            goToDetailScreen()
                        }}>
                    <i className="bi bi-bag fa-2x"/>
                </Nav.Link>
            </Nav.Item>
            {/*full screen icon*/}
            <Nav.Item className='ms-3 d-flex align-items-center justify-content-center'>
                {isFullscreen === true ?
                    <i className="bi bi-fullscreen-exit cursor-pointer text-white fs-1"
                       onClick={() => fullScreen()}/>
                    :
                    <i className="bi bi-arrows-fullscreen cursor-pointer text-white con fs-1"
                       onClick={() => fullScreen()}/>
                }
            </Nav.Item>
            {/* {Calculator} */}
            <Nav.Item className='d-flex align-items-center justify-content-center ms-3'>
                <i class="bi bi-calculator cursor-pointer text-white fa-2x"
                   onClick={opneCalculatorModel}/>
            </Nav.Item>
            {/*{dashboard redirect icon}*/}
            <Nav.Item className='d-flex align-items-center justify-content-center ms-3'>
                <Nav.Link href='/#/' className='pe-0 ps-1 text-white'>
                    <i className="bi bi-speedometer2 cursor-pointer fa-2x"/>
                </Nav.Link>
            </Nav.Item>
        </Nav>
        {/* </Col> */}
        {opneCalculator && <PosCalculator />}
        </>

    )
};

export default HeaderAllButton;
