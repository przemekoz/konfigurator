<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Konfigurator</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<?php echo Asset::css('bootstrap.min.css'); ?>
	<?php echo Asset::css('hello.css'); ?>
	<?php
	$js = array();
	\Fuel::$env == 'development' ? array_push($js, 'vue.js') : array_push($js, 'vue.min.js');
	array_push($js,
		'jquery-1.9.1.min.js',
		'bootstrap.min.js',
		'underscore-min.js',
		'vue-carousel-3d.min.js',
		'vue-resource.min.js'
	);
	echo Asset::js($js); ?>
	<script type="text/javascript">
	$(function () {
	  $('[data-toggle="tooltip"]').tooltip();
	})
	</script>
</head>
<body>
	<div class="container">


		<!-- Vue -->
		<div id="vueApp">

				<div class="row">
					<div class="col-sm-2">

						<div id="filter">
							<div class="form-horizontal">
								<p>
									<div class="radio">
											<label>
												<input type="radio" value="CITY" v-model="filter.category">
												<strong v-if="filter.category == 'CITY'" class="city-color">{{'CITY_STREET' | dict}}</strong>
												<span v-else class="city-color">{{'CITY_STREET' | dict}}</span>
											</label>
									</div>
									<div class="radio">
										<label>
											<input type="radio" value="HOME" v-model="filter.category">
											<strong v-if="filter.category == 'HOME'" class="home-color">{{'HOME_GARDEN' | dict}}</strong>
											<span v-else class="home-color">{{'HOME_GARDEN' | dict}}</span>
										</label>
									</div>
								</p>
								<p>
									<div class="radio">
											<label>
												<input type="radio" value="LAMP" v-model="elementType">
												<strong v-if="elementType == 'LAMP'">{{'FILTER_LAMP' | dict}}</strong>
												<span v-else>{{'FILTER_LAMP' | dict}}</span>
											</label>
											<div>
												<label>
													<input type="checkbox" value="WITH_CROWN" v-model="withCrown" @change="changedFilter()">
													{{'FILTER_WITH_CROWN' | dict}}
												</label>
											</div>
									</div>
									<div class="radio">
											<label>
												<input type="radio" value="KINKIET" v-model="elementType">
												<strong v-if="elementType == 'KINKIET'">{{'FILTER_KINKIET' | dict}}</strong>
												<span v-else>{{'FILTER_KINKIET' | dict}}</span>
											</label>
									</div>
									<div class="radio">
											<label>
												<input type="radio" value="OTHER" v-model="elementType">
												<strong v-if="elementType == 'OTHER'">{{'FILTER_OTHER' | dict}}</strong>
												<span v-else>{{'FILTER_OTHER' | dict}}</span>
											</label>
									</div>
								</p>
								<p v-if="elementType == 'LAMP'">
									<label for="size">{{'SELECT_ONE_SIZE' | dict}}</label>
									<select id="size" class="form-control" v-model="filter.size" @change="changedFilter()">
										<option disabled value="0">-</option>
									  <option v-for="option in sizes" :value="option.id" :key="option.id">
									    {{ option.text }}
									  </option>
									</select>
								</p>
								<p v-if="elementType == 'LAMP'">
									<label for="material">{{'SELECT_ONE_MATERIAL' | dict}}</label>
									<select id="material" class="form-control" v-model="filter.material" @change="changedFilter()">
										<option disabled value="0">-</option>
									  <option v-for="option in materials" :value="option.id" :key="option.id">
									    {{ option.text | dict}}
									  </option>
									</select>
								</p>
								<p v-if="elementType != 'OTHER'">
									<label for="connection">{{'SELECT_ONE_CONNECTION' | dict}}</label>
									<select id="connection" class="form-control" v-model="filter.connection">
										<option disabled value="">-</option>
										<option value="UP">{{'FILTER_UP' | dict}}</option>
										<option value="DOWN">{{'FILTER_DOWN' | dict}}</option>
									</select>
								</p>

							  <button @click="changedFilter()" class="btn btn-default btn-success btn-sm">
									<span class="glyphicon glyphicon-refresh"></span>
									{{'SEARCH' | dict}}
								</button>
							</div>
						</div>
					</div>
					<div class="col-sm-6">

						<div v-if="chooser" class="height-container">

							<div v-if="!columns.length && !crowns.length && !lamps.length && !others.length" class="flex-container-center height-container">
								<span>{{'NO_DATA' | dict}}</span>
							</div>

							<div v-if="elementType == 'LAMP'">
									<carousel-3d :on-slide-change="lampChanged" :controls-visible="false" :perspective="0" :width="320" :height="240" :display="3" :inverse-scaling="1000" :space="400" :border="1">
										<slide v-for="(slide, i) in lamps" :key="i" :index="i" :class="{'city-border': isCity(), 'home-border': isHome()}">
											<div class="slide-container">
												<div class="padding">
													<div class="title">
														<span>{{slide.name}}</span><br>
														<span>{{slide.image_size_x}} x {{slide.image_size_y}}</span>
													</div>
													<div class="index">
														<span>{{i + 1}} {{'OF' | dict}} {{lamps.length}}</span><br>
													</div>
													<div class="image">
														<span class="helper"><img :src="slide.src" /></span>
													</div>
												</div>
											</div>
										</slide>
									</carousel-3d>

									<carousel-3d v-if="withCrown" :on-slide-change="crownChanged" :controls-visible="false" :perspective="0" :width="320" :height="240" :display="3" :inverse-scaling="1000" :space="400" :border="1">
										<slide v-for="(slide, i) in crowns" :key="i" :index="i" :class="{'city-border': isCity(), 'home-border': isHome()}">
											<div class="slide-container">
												<div class="padding">
													<div class="title">
														<span>{{slide.name}}</span><br>
														<span>{{slide.image_size_x}} x {{slide.image_size_y}}</span>
													</div>
													<div class="index">
														<span>{{i + 1}} {{'OF' | dict}} {{crowns.length}}</span><br>
													</div>
													<div class="image">
														<span class="helper"><img :src="slide.src" /></span>
													</div>
												</div>
											</div>
										</slide>
									</carousel-3d>

									<carousel-3d :on-slide-change="columnChanged" :controls-visible="false" :perspective="0" :width="320" :height="240" :display="3" :inverse-scaling="1000" :space="400" :border="1">
										<slide v-for="(slide, i) in columns" :key="i" :index="i" :class="{'city-border': isCity(), 'home-border': isHome()}">
											<div class="slide-container">
												<div class="padding">
													<div class="title">
														<span>{{slide.name}}</span><br>
														<span>{{slide.image_size_x}} x {{slide.image_size_y}}</span>
													</div>
													<div class="index">
														<span>{{i + 1}} {{'OF' | dict}} {{columns.length}}</span><br>
													</div>
													<div class="image">
														<span class="helper"><img :src="slide.src" /></span>
													</div>
												</div>
											</div>
										</slide>
									</carousel-3d>
							</div>

							<div v-if="elementType == 'KINKIET'">
									<carousel-3d :on-slide-change="lampChanged" :controls-visible="false" :perspective="0" :width="320" :height="240" :display="3" :inverse-scaling="1000" :space="400" :border="1">
										<slide v-for="(slide, i) in lamps" :key="i" :index="i" :class="{'city-border': isCity(), 'home-border': isHome()}">
											<div class="slide-container">
												<div class="padding">
													<div class="title">
														<span>{{slide.name}}</span><br>
														<span>{{slide.image_size_x}} x {{slide.image_size_y}}</span>
													</div>
													<div class="index">
														<span>{{i + 1}} {{'OF' | dict}} {{lamps.length}}</span><br>
													</div>
													<div class="image">
														<span class="helper"><img :src="slide.src" /></span>
													</div>
												</div>
											</div>
										</slide>
									</carousel-3d>

									<carousel-3d :on-slide-change="kinkietChanged" :controls-visible="false" :perspective="0" :width="320" :height="240" :display="3" :inverse-scaling="1000" :space="400" :border="1">
										<slide v-for="(slide, i) in kinkiet" :key="i" :index="i" :class="{'city-border': isCity(), 'home-border': isHome()}">
											<div class="slide-container">
												<div class="padding">
													<div class="title">
														<span>{{slide.name}}</span><br>
														<span>{{slide.image_size_x}} x {{slide.image_size_y}}</span>
													</div>
													<div class="index">
														<span>{{i + 1}} {{'OF' | dict}} {{kinkiet.length}}</span><br>
													</div>
													<div class="image">
														<span class="helper"><img :src="slide.src" /></span>
													</div>
												</div>
											</div>
										</slide>
									</carousel-3d>
							</div>

							<div v-if="elementType == 'OTHER'">
									<carousel-3d :on-slide-change="otherChanged" :controls-visible="false" :perspective="0" :width="320" :height="240" :display="3" :inverse-scaling="1000" :space="400" :border="1">
										<slide v-for="(slide, i) in others" :key="i" :index="i" :class="{'city-border': isCity(), 'home-border': isHome()}">
											<div class="slide-container">
												<div class="padding">
													<div class="title">
														<span>{{slide.name}}</span><br>
														<span>{{slide.image_size_x}} x {{slide.image_size_y}}</span>
													</div>
													<div class="index">
														<span>{{i + 1}} {{'OF' | dict}} {{others.length}}</span><br>
													</div>
													<div class="image">
														<span class="helper"><img :src="slide.src" /></span>
													</div>
												</div>
											</div>
										</slide>
									</carousel-3d>
							</div>

						</div>

						<div v-else class="flex-container-center height-container">
							<i class="fa fa-cog fa-spin" style="font-size:24px; color: #aaa"></i>
						</div>



					</div>
					<div class="col-md-4">
						<!-- <hr style="margin-top:20%; width: 1px; height: 260px; display: inline-block; background-color: #ddd"> -->
						<p></p>

						<div v-show="preview && preview != 'NO_PREVIEW'">
							<button @click="getPreview()" class="btn btn-default btn-sm">
								<span class="glyphicon glyphicon-eye-open"></span>
								{{'PREVIEW' | dict}}
							</button>
							<button data-toggle="tooltip" title="Some tooltip text!" @click="getPdf()" class="btn btn-default btn-sm">
								<span class="glyphicon glyphicon-save-file"></span>
								{{'PDF' | dict}}
							</button>
							<button type="button" class="btn btn-default btn-sm" data-toggle="modal" @click="clearEmailForm()" data-target="#myModal">
							  <i class="glyphicon glyphicon-send"></i> {{'INQUIRY' | dict}}
							</button>
						</div>

						<div class="flex-container-center height-container" :class="{'preview-lamp' : isLamp(), 'preview-other' : isNotLamp()}">
							<div class="padding">
								<span v-if="preview == 'NO_PREVIEW'">{{'NO_PREVIEW' | dict}}</span>
								<span v-else>
										<img v-if="preview" :src="preview">
										<i v-else class="fa fa-cog fa-spin" style="font-size:24px; color: #aaa"></i>
								</span>
							</div>
						</div>
					</div>
				</div>

				<!-- Modal -->
				<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				  <div class="modal-dialog" role="document">
				    <div class="modal-content">
				      <div class="modal-header">
				        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				        <h4 class="modal-title" id="myModalLabel">{{'INQUIRY' | dict}}</h4>
				      </div>
				      <div class="modal-body">

				        <form>

				          <div class="form-group">
				            <label for="email">{{'EMAIL' | dict}}</label>
				            <input type="email" v-model="email" class="form-control" id="email" :placeholder="dict('EMAIL_PLACEHOLDER')" autofocus>
										<p v-if="emailFail" class="bg-danger" style="padding: 15px">
											{{'EMAIL_IS_REQUIRED' | dict}}
										</p>
				          </div>

				          <div class="form-group">
				            <label for="name">{{'NAME' | dict}}</label> <small>({{'OPTIONAL' | dict}})</small>
				            <input type="text" v-model="name" class="form-control" id="name" :placeholder="dict('NAME_PLACEHOLDER')">
				          </div>

				          <div class="form-group">
				            <label for="message">{{'MESSAGE' | dict}}</label> <small>({{'OPTIONAL' | dict}})</small>
				            <textarea id="message" v-model="emailMessage" class="form-control" rows="3"></textarea>
				          </div>

				        </form>

				      </div>
				      <div class="modal-footer">
								<p v-if="mailSending" class="bg-info" style="padding: 15px">
									{{'MAIL_IS_SENDING' | dict}}
								</p>
								<p v-if="mailSended" class="bg-success" style="padding: 15px">
									{{'MAIL_SEND_MESSAGE' | dict}}
								</p>
				        <a href="#closeModal" data-dismiss="modal">{{'CLOSE' | dict}}</a>
				        <button v-if="!mailSending && !mailSended" type="button" class="btn btn-primary" @click="sendMail()">
									{{'SEND' | dict}}
								</button>
				      </div>
				    </div>
				  </div>
				</div>

			</div>
			<!-- Vue -->
	</div>
<script type="text/javascript">
const BASE_URL = '<?php echo Helper::getBaseUrl(); ?>';
const LANG = '<?php echo $paramLang; ?>';
</script>
<script type="text/javascript" src="assets/js/hello.js"></script>
</body>
</html>