import { HiPlay } from 'react-icons/hi';
import { StoreIcon } from './StoreIcon';

const Applink = (props) => {
	return (
		<div className={`litoal litoal-${props.app.type}`}>
			<div className='litoal-link'>
				<a
					className='litoal-figure'
					href={props.app.url}
					target='_blank'
					rel='noopener nofollow noreferrer'
				>
					<img
						className='litoal-img'
						src={props.app.iconUrl}
						alt={props.app.title}
					/>
				</a>
				<div className='litoal-content'>
					<a
						className='litoal-title'
						href={props.app.url}
						target='_blank'
						rel='noopener nofollow noreferrer'
					>
						{props.app.title}
					</a>
					<div className='litoal-artist'>{props.app.artist}</div>

					<div className='litoal-btns'>
						{props.app.previewUrl && (
							<a
								className='litoal-audition litoal-btn'
								href={props.app.previewUrl}
								target='_blank'
								rel='noopener nofollow noreferrer'
							>
								<HiPlay />
								<span className='litoal-btn-label'>試聴</span>
							</a>
						)}
						<a
							className='litoal-store litoal-btn'
							href={props.app.url}
							target='_blank'
							rel='noopener nofollow noreferrer'
						>
							<StoreIcon type={props.app.type} />
						</a>
					</div>
				</div>
			</div>
		</div>
	);
};

export default Applink;
