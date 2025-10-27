import { HiPlay } from 'react-icons/hi';
import { StoreIcon } from './StoreIcon';

const Applink = (props) => {
	return (
		<div className={`alfwp alfwp-${props.app.type}`}>
			<div className='alfwp-link'>
				<a
					className='alfwp-figure'
					href={props.app.url}
					target='_blank'
					rel='noopener nofollow noreferrer'
				>
					<img
						className='alfwp-img'
						src={props.app.iconUrl}
						alt={props.app.title}
					/>
				</a>
				<div className='alfwp-content'>
					<a
						className='alfwp-title'
						href={props.app.url}
						target='_blank'
						rel='noopener nofollow noreferrer'
					>
						{props.app.title}
					</a>
					<div className='alfwp-artist'>{props.app.artist}</div>

					<div className='alfwp-btns'>
						{props.app.previewUrl && (
							<a
								className='alfwp-audition alfwp-btn'
								href={props.app.previewUrl}
								target='_blank'
								rel='noopener nofollow noreferrer'
							>
								<HiPlay />
								<span className='alfwp-btn-label'>試聴</span>
							</a>
						)}
						<a
							className='alfwp-store alfwp-btn'
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
